<?php

session_start();

include 'porterStemmer.php';
include '../php/database.php';

$database = connectToDB();

//First, try to get a brand new file from the post data
$file = $_FILES["teiFile"]["tmp_name"];
$doc = new DOMDocument();
$success = $doc->load( $file );
$uploaded = true;

//If there's no file there, look for a link to an existing file
//and grab it out of the database
if (!$success and array_key_exists('id', $_GET))
{
    $escapedId = mysql_real_escape_string($_GET['id']);
    $result = mysql_query("SELECT xml FROM Document where id='$escapedId'");
    if ($row = mysql_fetch_array($result))
    {
        $doc->loadXML($row['xml']);
        $success = true;
        $uploaded = false;
    }
}

if (!$success)
{
    print "<h1>Error</h1>There was a problem handling your request<br>
    <a href='index.php'>Upload a new file</a><br>
    <a href='browse.php'>Browse existing files</a><br>";
    die();
}

$_SESSION['teiXml'] = $doc->saveXML();

print "<h1>Editing File " . $_FILES["teiFile"]["name"] . "</h1>";
$title = $doc->getElementsByTagName("title")->item(0)->textContent;
print "<h2>$title</h2>";
if ($uploaded)
{
    print "Before adding this document to the database, help us tag the referenced people and places.<br><br><br>";
}
print "<div style='background:#E9C2A6; display:inline-block'>";

$docId = $doc->documentElement->attributes->getNamedItem("id")->nodeValue;
//print "INSERT INTO Document (docId, creation, citation, digitalCreation, title)";
print "Document ID: $docId<br>";

//Find the date of digital creation
$pubElement = $doc->getElementsByTagName("publicationStmt")->item(0);
$digitalDateElement = $pubElement->getElementsByTagName("date")->item(0);
$digitalDate = $digitalDateElement->attributes->getNamedItem("when")->nodeValue;
print "Digitally created: $digitalDate <br>";

//Find the document's original creation date
$creationElement = $doc->getElementsByTagName("creation")->item(0);
$creationDateElement = $creationElement->getElementsByTagName("date")->item(0);
$creationDate = $creationDateElement->attributes->getNamedItem("when")->nodeValue;
print "Originally created: $creationDate <br>";

//Find the authors of the original document
$authors = $doc->getElementsByTagName("author");
foreach ($authors as $author)
{
    print "Author:";
    print $author->textContent;
    print "<br>";
}

?>
<table border="1"><tr><td>Document Location</td><td>Name</td><td>Normalized</td><td>Suggested</td></tr>
<?php

#Returns an array of character n-grams from the target string
function nCharGrams($targetString, $n)
{
    $grams = array();
    $chars = str_split($targetString);
    for($i = 0; $i < count($chars) - $n + 1; $i++)
    {
        $gram = '';
        for ($j = 0; $j < $n; $j++)
        {
            $gram .= $chars[$i + $j];
        }
        array_push($grams, $gram);
    }
    return $grams;
}

#Returns the Jaccard Similarity Coefficient of two strings
#http://en.wikipedia.org/wiki/Jaccard_index
#a and b are short strings, approximately equal in length,
#to be compared. The returned value is between 0 and 1.
#Identical strings return 1.
function jaccardSim($a, $b)
{
    $gramsA = nCharGrams(strtolower($a), 2);
    $gramsB = nCharGrams(strtolower($b), 2);
    $intersect = count(array_unique(array_intersect($gramsA, $gramsB)));
    $union = count(array_unique(array_merge($gramsA, $gramsB)));
    return $intersect / $union;
}

//Creates a row in the table of people appearing in the document
function makePersonRow($location, $personName, $key, $knownNames, $index, $dropdownName) {
    $normalizedName = "(Not Tagged)";
    $suggestion = "~";
    if ($key != NULL)
    {
        if (array_key_exists($key, $knownNames))
        {
            $normalizedName = $knownNames[$key][0];
        }
    }
    $suggestion = "";
    $bestScore = 0.4;
    $bestId = '';
    foreach($knownNames as $id => $forms)
    {
        $standardForm = $forms[0];
        foreach($forms as $form)
        {
            $score = jaccardSim($personName, $form);
            if ($score > $bestScore)
            {
                if ($form == $standardForm)
                {
                    $suggestion = $standardForm;
                }
                else
                {
                    $suggestion = "$standardForm (aka $form)";
                }
                $bestId = $id;
                $bestScore = $score;
            }
        }
    }
    print "<tr><td>$location</td><td>$personName</td>";
    print "<td><select id='$dropdownName$index' name='${dropdownName}[]'><option value=''>(Not Tagged)</option>";
    foreach ($knownNames as $id => $forms)
    {
        print "<option";
        if ($id == $key)
        {
            print " selected=\"selected\"";
        }
        $standardForm = $forms[0];
        print " value='$id'>$standardForm</option>";
    }
    print "</select></td><td align='right'>";

    if ($bestId != '')
    {
        print "$suggestion ($bestId)<button type='button' onClick=\"document.getElementById('$dropdownName$index').value='$bestId'\">Confirm</button>";
    }
    else
    {
        print "No suggestions";
    }
    print "</td></tr>";
}

//Generate lists of normalized people and places for comparison to the input.
$knownNames = array();
$result = mysql_query("SELECT id, name FROM NormalizedPerson ORDER BY name");
while ($row = mysql_fetch_array($result))
{
    $knownNames[$row['id']] = array($row['name']);
}
$knownPlaces = array();
$result = mysql_query("SELECT id, name FROM NormalizedPlace ORDER BY name");
while ($row = mysql_fetch_array($result))
{
    $knownPlaces[$row['id']] = array($row['name']);
}
$result = mysql_query("SELECT normalId, text FROM PlaceReference where normalId IS NOT NULL");
while ($row = mysql_fetch_array($result))
{
    array_push($knownPlaces[$row['normalId']], $row['text']);
}

print "<form action='submit.php' method='POST'>";

//Create the table of people with suggested normalized values
$people = $doc->getElementsByTagName("persName");
$index = 0;
foreach( $people as $person )
{
    $name = $person->textContent;
    $type = $person->attributes->getNamedItem("type")->nodeValue;
    $key = $person->attributes->getNamedItem("key")->nodeValue;
    $dropdownName = 'personTag';
    if ($person->parentNode->nodeName == "author")
    {
        makePersonRow("Author", $name, $key, $knownNames, $index, $dropdownName);
    }
    elseif ($type == "recipient")
    {
        makePersonRow("Receiver", $name, $key, $knownNames, $index, $dropdownName);
    }
    elseif ($type == "sender")
    {
        makePersonRow("Sender", $name, $key, $knownNames, $index, $dropdownName);
    }
    else
    {
        $highestParent = $person;
        while ($highestParent->parentNode->parentNode->parentNode)
        {
            $highestParent = $highestParent->parentNode;
        }
        makePersonRow($highestParent->nodeName, $name, $key, $knownNames, $index, $dropdownName);
    }
    $index += 1;
}

//Create a table for tagging places
$places = $doc->getElementsByTagName("placeName");
$index = 0;
foreach( $places as $place )
{
    $name = $place->textContent;
    $type = $place->attributes->getNamedItem("type")->nodeValue;
    $key = $place->attributes->getNamedItem("key")->nodeValue;
    $dropdownName = 'placeTag';
    if ($type == "origin")
    {
        makePersonRow("Origin", $name, $key, $knownPlaces, $index, $dropdownName);
    }
    elseif ($type == "destination")
    {
        makePersonRow("Destination", $name, $key, $knownPlaces, $index, $dropdownName);
    }
    else
    {
        $highestParent = $person;
        while ($highestParent->parentNode->parentNode->parentNode)
        {
            $highestParent = $highestParent->parentNode;
        }
        makePersonRow($highestParent->nodeName, $name, $key, $knownPlaces, $index, $dropdownName);
    }
    $index += 1;
}


?>
</table>
<br>
<center>
<input type="submit" value="Save Changes" style="font-size:120%"/>
<a href="browse.php">
<button type="button" style="font-size:120%">Cancel</button>
</a>
</form>
</center>
</div>

