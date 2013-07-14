<?php

// This script is responsible for taking a query from the user and generating
// a JSON array containing the results.
//
// The results are based on the following fields in the GET data:
//      query - The text that the user is searching for.  This text will
//          be split on non-word characters and then stemmed.
//      location - A place ID.  If given and not -1, results will only be
//          returned if they were either sent from or to the specified location
//
// Example potential output:
//  [{"id": 4321, "title":"First Result Title",
//  "summary":"This is a summary of the first
//  result", "date":"2012-5-16", "cityId":1234}]
//

#Returns an array of character n-grams from the target string


session_start();

include '../php/porterStemmer.php';
include '../php/database.php';

$database = connectToDB();

//Stem and split the query
$stemmer = new PorterStemmer();
$text = trim(strtolower($_GET['query']));
//Split on non-word characters
logString("text={$text}");


$words = preg_split('/[\W]+/', $text);
//Stem each word
logString("words={$words}");


$stems = array_map(array($stemmer, 'Stem'), $words);
$stemCounts = array_count_values($stems);

$stemCondition = " stem in (NULL";
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



$fromDateCondition = '';
if (array_key_exists('fromYear', $_GET) && ($_GET['fromYear'] != ''))
{
    $escapedFromYear = mysql_real_escape_string($_GET['fromYear']);
    $fromDateCondition = " AND creation > '$escapedFromYear' ";
}

$toDateCondition = '';
if (array_key_exists('toYear', $_GET) && ($_GET['toYear'] != ''))
{
    $escapedToYear = mysql_real_escape_string($_GET['toYear']);
	$escapedToYear = $escapedToYear + 1; # date 
	
    $toDateCondition = " AND creation < '$escapedToYear' ";
}

$orderBy = '';
if (array_key_exists('sort', $_GET)) {
    if ($_GET['sort'] === 'similarity') {
        $orderBy = ' ORDER BY similarity ';
    }
    elseif ($_GET['sort'] === 'date') {
        $orderBy = ' ORDER BY date ';
    }
}

$sql = "
    SELECT Document.id as id, Document.summary as summary,
        Document.title as title, Document.creation as date,
        sum(StemCount.tfIdf) / Document.vectorLength as similarity,
        Destination.lat as dstLat, Destination.lng as dstLng,
        Source.lat as srcLat, Source.lng as srcLng
    FROM Document
    INNER JOIN StemCount ON StemCount.docId = Document.id
    LEFT OUTER JOIN NormalizedPlace AS Destination
        ON Document.sentToPlace = Destination.id
    LEFT OUTER JOIN NormalizedPlace AS Source
        ON Document.sentFromPlace = Source.id
    WHERE
    $stemCondition
    $locationCondition
    $fromDateCondition
    $toDateCondition
        GROUP BY Document.Id
    $orderBy ;
";

logString($sql);

$docData = array();
$result = mysql_query($sql) or die($sql . "<br>" . mysql_error());
while ($row = mysql_fetch_assoc($result))
{
    array_push($docData, $row);
}
print json_encode($docData);

?>



