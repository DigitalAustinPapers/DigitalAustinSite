<?php

if (!isset($_SESSION)) {
    session_start();
}

require_once('porterStemmer.php');
require_once('../php/database.php');

if (!function_exists('removeDuplicateSpaces'))
{
	function removeDuplicateSpaces($word)
	{
	    $pattern = '/\s+/';
	    $replacement = ' ';
	    return preg_replace($pattern, $replacement, $word);
	}
}

if (!isset($database)) {
    $database = connectToDB();
}

//Load the pared TEI file from the user's session, or die trying.
$doc = new DOMDocument();
$success = ($doc->loadXML($_SESSION['teiXml']));
if (!$success)
{
    print "<h1>Error</h1>Your session has expired, sorry!  You'll have to start over.<br>
    <a href='index.php'>Back</a><br>";
    die();
}

//Find the document's id
$docId = $doc->documentElement->attributes->getNamedItem("id")->nodeValue;

//Find the document's title
$title = $doc->getElementsByTagName("title")->item(0)->textContent;
$title = removeDuplicateSpaces($title);

//Find the date of digital creation
$pubElement = $doc->getElementsByTagName("publicationStmt")->item(0);
$digitalDateElement = $pubElement->getElementsByTagName("date")->item(0);
$digitalDate = $digitalDateElement->attributes->getNamedItem("when")->nodeValue;

//Find the document's original creation date
$creationElement = $doc->getElementsByTagName("creation")->item(0);
$creationDateElement = $creationElement->getElementsByTagName("date")->item(0);
$creationDate = $creationDateElement->attributes->getNamedItem("when")->nodeValue;

//Get the document's summary
$div1Elements = $doc->getElementsByTagName("div1");
$summary = '';
for ($i = 0; $i < $div1Elements->length; ++$i)
{
    if ($div1Elements->item($i)->attributes->getNamedItem('type')->nodeValue == 'summary')
    {
        $summary .= $div1Elements->item($i)->textContent;
    }
}
$summary = removeDuplicateSpaces($summary);

$result = mysql_query("SELECT id, name FROM NormalizedPerson");
$knownNames = array();
while ($row = mysql_fetch_array($result))
{
    $knownNames[$row['id']] = $row['name'];
}

//Attach the chosen keys to the person tags
//Also, build up an sql insert for person references
$textElement = $doc->getElementsByTagName("text")->item(0);
$people = $textElement->getElementsByTagName("persName");
$index = 0;
$first = true;
$personSql = '';
foreach( $people as $person )
{
    if ($first)
    {
        $personSql = 'INSERT INTO PersonReference (docId, text, normalId) VALUES ';
        $first = false;
    }
    else
    {
        $personSql .= ", ";
    }
    $text = removeDuplicateSpaces($person->textContent);
    $type = '';
    $typeNode = $place->attributes->getNamedItem("type");
    if ($typeNode)
    {
        $type = $typeNode->nodeValue;
    }
    $key = '';
    $keyNode = $place->attributes->getNamedItem("key");
    if ($keyNode)
    {
        $key = $keyNode->nodeValue;
    }
    if ($_POST["personTag"][$index])
    {
        $keyAttr = $doc->createAttribute("key");
        $keyAttr->value = strval($_POST["personTag"][$index]);
        $person->appendChild($keyAttr);
        $key = $_POST["personTag"][$index];
    }
    $escapedDocId = mysql_real_escape_string($docId);
    $escapedText = mysql_real_escape_string($text);
    $personSql .= "('$escapedDocId', '$escapedText', ";
    if ($key == NULL)
    {
        $personSql .= "NULL)";
    }
    else
    {
        $escapedKey = mysql_real_escape_string($key);
        $personSql .= "$escapedKey)";
    }
    $index += 1;
}

//Attach the chosen keys to the place tags
//Also, build up an sql insert for place references
$places = $doc->getElementsByTagName("placeName");
$index = 0;
$first = true;
$placeSql = '';
$escapedQuotedSentToKey = 'NULL';
$escapedQuotedSentFromKey = 'NULL';
foreach( $places as $place )
{
    if ($first)
    {
        $placeSql = 'INSERT INTO PlaceReference (docId, text, normalId) VALUES ';
        $first = false;
    }
    else
    {
        $placeSql .= ", ";
    }
    $text = removeDuplicateSpaces($place->textContent);
    $type = '';
    $typeNode = $place->attributes->getNamedItem("type");
    if ($typeNode)
    {
        $type = $typeNode->nodeValue;
    }
    $key = '';
    $keyNode = $place->attributes->getNamedItem("key");
    if ($keyNode)
    {
        $key = $keyNode->nodeValue;
    }

    if ($_POST["placeTag"][$index])
    {
        $keyAttr = $doc->createAttribute("key");
        $keyAttr->value = strval($_POST["placeTag"][$index]);
        $place->appendChild($keyAttr);
        $key = $_POST["placeTag"][$index];
        if ($type == "origin")
        {
            $escapedQuotedSentFromKey = "'" . mysql_real_escape_string($key) . "'";
        }
        elseif ($type == "destination")
        {
            $escapedQuotedSentToKey = "'" . mysql_real_escape_string($key) . "'";
        }
    }
    $escapedDocId = mysql_real_escape_string($docId);
    $escapedText = mysql_real_escape_string($text);
    $placeSql .= "('$escapedDocId', '$escapedText', ";
    if ($key == NULL)
    {
        $placeSql .= "NULL)";
    }
    else
    {
        $escapedKey = mysql_real_escape_string($key);
        $placeSql .= "$escapedKey)";
    }
    $index += 1;
}

//Porter stemming and record the stem counts in the database
$textElement = $doc->getElementsByTagName("text")->item(0);
$stemmer = new PorterStemmer();
$text = trim(strtolower($textElement->textContent));
//Split on non-word characters
$words = preg_split('/[\W]+/', $text);

//Build the SQL insert statement for the WordCount table
$wordCounts = array_count_values($words);
$wordCountInsert = "INSERT INTO WordCount (docId, word, count) VALUES ";
$first = true;
foreach($wordCounts as $word => $count)
{
    if ($first)
    {
        $first = false;
    }
    else
    {
        $wordCountInsert .= ", ";
    }
    $escapedDocId = mysql_real_escape_string($docId);
    $escapedWord = mysql_real_escape_string($word);
    $wordCountInsert .= "('$escapedDocId', '$escapedWord', $count)";
}
$wordCountInsert .= " ON DUPLICATE KEY UPDATE count=VALUES(count)";


//Stem each word
$stems = array_map(array($stemmer, 'Stem'), $words);
$stemCounts = array_count_values($stems);
//Build the SQL insert statement for the StemCount table
$stemCountInsert = "INSERT INTO StemCount (docId, stem, count) VALUES ";
$first = true;
foreach($stemCounts as $stem => $count)
{
    if ($first)
    {
        $first = false;
    }
    else
    {
        $stemCountInsert .= ", ";
    }
    $escapedDocId = mysql_real_escape_string($docId);
    $escapedStem = mysql_real_escape_string($stem);
    $stemCountInsert .= "('$escapedDocId', '$escapedStem', $count)";
}
$stemCountInsert .= " ON DUPLICATE KEY UPDATE count=VALUES(count)";

//Insert the document into the database
$escapedId = mysql_real_escape_string($docId);
$escapedTitle = mysql_real_escape_string($title);
$escapedXml = mysql_real_escape_string($doc->saveXML());
$escapedCreation = mysql_real_escape_string($creationDate); 
$escapedSummary = mysql_real_escape_string($summary);
$insertDocSql = "REPLACE INTO Document (id, title, xml,
    creation, summary, sentToPlace, sentFromPlace) VALUES ('$escapedId', '$escapedTitle',
    '$escapedXml', '$escapedCreation', '$escapedSummary', $escapedQuotedSentToKey, $escapedQuotedSentFromKey)";

mysql_query($insertDocSql) or print(mysql_error());

//Perform the PersonReference insert
mysql_query($personSql) or print(mysql_error());

//Perform the PlaceReference insert
mysql_query($placeSql) or print(mysql_error());

//Perform the WordCount insert
mysql_query($wordCountInsert) or print(mysql_error());

//Perform the StemCount insert
mysql_query($stemCountInsert) or print(mysql_error());

if (!$command_line)
{
    header("Location: browse.php?saved=$docId");
}

?>
