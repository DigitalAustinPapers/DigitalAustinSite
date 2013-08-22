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

// handle "bad" dates
if(preg_match("/\d\d\d\d-\d\d$/", $creationDate)) {
	$creationDate .= "-00";
}

if(preg_match("/\d\d\d\d$/", $creationDate)) {
	$creationDate .= "-00-00";
}


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

#logString("loading normalized persons for some reason");

$result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id, name FROM NormalizedPerson");
$knownNames = array();
while ($row = mysqli_fetch_array($result))
{
    $knownNames[$row['id']] = $row['name'];
}

//Attach the chosen keys to the person tags
//Also, build up an sql insert for person references

# BatchSubmit attempts to identify all persons within the document, not just the persons within the text!
#$textElement = $doc->getElementsByTagName("text")->item(0);
$people = $doc->getElementsByTagName("persName");
$index = 0;
$first = true;
$personSql = '';
$escapedQuotedSentToPersonKey = 'NULL';
$escapedQuotedSentFromPersonKey = 'NULL';

foreach( $people as $person )
{
	$skip = false;
#	logString("looping on index={$index}, person={$person->textContent}");
	
    $text = removeDuplicateSpaces($person->textContent);
    $type = '';
    $typeNode = $person->attributes->getNamedItem("type");
	$parentNodeName = $person->parentNode->nodeName;

	if($parentNodeName=="respStmt") {
		$skip = true;
	}


#	logString("parentNodename for {$text} was {$parentNodeName}");
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
		
    	//logString("type={$type} and key={$key} for text={$text}");
		if($type=="recipient" && $key != '') {
			$escapedQuotedSentToPersonKey = $key;
		}

		if($parentNodeName=="author" && $key != '') {
			$escapedQuotedSentFromPersonKey = $key;
		}
		


    }
	if(!$skip) {
	    if ($first)
	    {
	        $personSql = 'INSERT INTO PersonReference (docId, text, normalId) VALUES ';
	        $first = false;
	    }
	    else
	    {
	        $personSql .= ", ";
	    }
	    $escapedDocId = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $docId) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	    $escapedText = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $text) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	    $personSql .= "('$escapedDocId', '$escapedText', ";
	    if ($key == NULL)
	    {
	        $personSql .= "NULL)";
	    }
	    else
	    {
	        $escapedKey = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $key) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	        $personSql .= "$escapedKey)";
	    }
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
            $escapedQuotedSentFromKey = "'" . ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $key) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : "")) . "'";
        }
        elseif ($type == "destination")
        {
            $escapedQuotedSentToKey = "'" . ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $key) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : "")) . "'";
        }
    }
    $escapedDocId = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $docId) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    $escapedText = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $text) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    $placeSql .= "('$escapedDocId', '$escapedText', ";
    if ($key == NULL)
    {
        $placeSql .= "NULL)";
    }
    else
    {
        $escapedKey = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $key) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
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
    $escapedDocId = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $docId) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    $escapedWord = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $word) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
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
    $escapedDocId = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $docId) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    $escapedStem = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $stem) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
    $stemCountInsert .= "('$escapedDocId', '$escapedStem', $count)";
}
$stemCountInsert .= " ON DUPLICATE KEY UPDATE count=VALUES(count)";

//Insert the document into the database
$escapedId = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $docId) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
$escapedTitle = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $title) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
$escapedXml = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $doc->saveXML()) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
$escapedCreation = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $creationDate) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : "")); 
$escapedSummary = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $summary) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
$insertDocSql = "REPLACE INTO Document (id, title, xml,
    creation, summary, sentToPlace, sentFromPlace, sentToPerson, sentFromPerson) VALUES ('$escapedId', '$escapedTitle',
    '$escapedXml', '$escapedCreation', '$escapedSummary', $escapedQuotedSentToKey, $escapedQuotedSentFromKey, $escapedQuotedSentToPersonKey, $escapedQuotedSentFromPersonKey)";

mysqli_query($GLOBALS["___mysqli_ston"], $insertDocSql) or print(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
//Perform the PersonReference insert
#logString($personSql);
mysqli_query($GLOBALS["___mysqli_ston"], $personSql) or print(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

//Perform the PlaceReference insert
mysqli_query($GLOBALS["___mysqli_ston"], $placeSql) or print(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

//Perform the WordCount insert
mysqli_query($GLOBALS["___mysqli_ston"], $wordCountInsert) or print(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

//Perform the StemCount insert
mysqli_query($GLOBALS["___mysqli_ston"], $stemCountInsert) or print(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

if (!$command_line)
{
    header("Location: browse.php?saved=$docId");
}

?>
