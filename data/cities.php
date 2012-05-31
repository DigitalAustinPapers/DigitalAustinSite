<?php

// This script is responsible for taking a query from the user and generating
// a JSON array containing the data necessary for the city markers
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

$database = connectToDB();

//Stem and split the query
$stemmer = new PorterStemmer();
$text = trim(strtolower($_GET['query']));
//Split on non-word characters
$words = preg_split('/[\W]+/', $text);
//Stem each word
$stems = array_map(array($stemmer, 'Stem'), $words);
$stemCounts = array_count_values($stems);

$stemCondition = " StemCount.stem in (NULL";
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
$sql = "
    SELECT T1.id as id, NormalizedPlace.name as name,
    sum(incoming) as incoming, sum(outgoing) as outgoing,
        incoming + outgoing as traffic,
        NormalizedPlace.lat as lat, NormalizedPlace.lng as lng FROM
    ((SELECT Document.sentToPlace as id, count(Document.id) as incoming,
        0 as outgoing
    FROM Document
    INNER JOIN StemCount ON StemCount.docId = Document.id
    WHERE
    $stemCondition
    $locationCondition
    GROUP BY Document.sentToPlace)
    UNION
    (SELECT Document.sentFromPlace as id, 0 as incoming,
        count(Document.id) as outgoing
    FROM Document
    INNER JOIN StemCount ON StemCount.docId = Document.id
    WHERE
    $stemCondition
    $locationCondition
    GROUP BY Document.sentFromPlace)) as T1
    INNER JOIN NormalizedPlace on T1.id = NormalizedPlace.id
    GROUP BY T1.id
    ORDER BY traffic DESC;
";

$docData = array();
$result = mysql_query($sql) or die($sql . "<br>" . mysql_error());
while ($row = mysql_fetch_assoc($result))
{
    array_push($docData, $row);
}
array_push($jsonOut, $docData);

print json_encode($docData);


?>



