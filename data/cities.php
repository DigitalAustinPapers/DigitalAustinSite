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
include '../data/query.php';

$database = connectToDB();

$sql = buildGeoSearchQuery();

$docData = array();
$result = mysql_query($sql) or die($sql . "<br>" . mysql_error());
while ($row = mysql_fetch_assoc($result))
{
    array_push($docData, $row);
}
//logString(json_encode($docData));
print json_encode($docData);


?>



