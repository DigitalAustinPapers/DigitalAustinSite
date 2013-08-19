<?php

// This script is responsible for taking a query from the user and generating
// a JSON array containing the data necessary for the cloud view.
//
// The results are based on the following fields in the GET data:
//      query - The text that the user is searching for.  This text will
//          be split on non-word characters and then stemmed.
//      location - A place ID.  If given and not -1, results will only be
//          returned if they were either sent from or to the specified location
//

session_start();

include '../php/porterStemmer.php';
include '../php/database.php';
include '../data/query.php';

$database = connectToDB();

# BWB TODO: This needs to be refactored to use the same documents as the document list results
logString("cloud.php start");

//Stem and split the query
$stemmer = new PorterStemmer();
$text = trim(strtolower($_GET['query']));
//Split on non-word characters
$words = preg_split('/[\W]+/', $text);
//Stem each word
$stems = array_map(array($stemmer, 'Stem'), $words);
$stemCounts = array_count_values($stems);

$stemCondition = " MatchingStems.stem in (NULL";
foreach ($stemCounts as $stem => $count)
{
    if (strlen($stem) != 0) {
        $escapedStem = mysql_real_escape_string($stem);
        $stemCondition .= ", '$escapedStem'";
    }
}
$stemCondition .= ')';

$locationCondition = '';
if (array_key_exists('location', $_GET) && ($_GET['location'] != -1))
{
    $escapedLocation = mysql_real_escape_string($_GET['location']);
    $locationCondition = " AND (sentToPlace=$escapedLocation
        OR sentFromPlace=$escapedLocation) ";
}

//Text Cloud
$sql = buildWordCloudSearchQuery();

$jsonOut = array();
$docData = array();
$result = mysql_query($sql) or die($sql . "<br>" . mysql_error());
while ($row = mysql_fetch_assoc($result))
{
    $row['link'] = 'results.php?query=' . urlencode($row['text']);
    array_push($docData, $row);
}
array_push($jsonOut, $docData);

//Place Cloud
$sql = "
    SELECT PlaceReference.text as text, count(PlaceReference.id) as weight
    FROM PlaceReference
    INNER JOIN Document ON Document.id = PlaceReference.docId
    INNER JOIN StemCount as MatchingStems
        ON MatchingStems.docId = Document.id
    WHERE
    $stemCondition
    $locationCondition
    GROUP BY PlaceReference.text
    ORDER BY weight DESC
    LIMIT 50;
";

$docData = array();
$result = mysql_query($sql) or die($sql . "<br>" . mysql_error());
while ($row = mysql_fetch_assoc($result))
{
    array_push($docData, $row);
}
array_push($jsonOut, $docData);

//Person Cloud
$sql = buildPersonCloudSearchQuery();
$docData = array();
$result = mysql_query($sql) or die($sql . "<br>" . mysql_error());
while ($row = mysql_fetch_assoc($result))
{
    $row['link'] = 'results.php?query=' . urlencode($row['text']);
    array_push($docData, $row);
}
array_push($jsonOut, $docData);
print json_encode($jsonOut);

logString("cloud.php end");

?>



