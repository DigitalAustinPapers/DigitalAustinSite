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
include_once '../php/database.php';
include '../data/query.php';

$database = connectToDB();

$sql = buildGeoSearchQuery();

$docData = array();
$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die($sql . "<br>" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
while ($row = mysqli_fetch_assoc($result))
{
    array_push($docData, $row);
}
//logString(json_encode($docData));
print json_encode($docData);


?>



