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
include '../data/query.php';
logString("search.php start");

$database = connectToDB();

$sql = buildDocumentSearchQuery();

$docData = array();
$result = mysql_query($sql) or die($sql . "<br>" . mysql_error());
while ($row = mysql_fetch_assoc($result))
{
    array_push($docData, $row);
}
print json_encode($docData);

logString("search.php end");

?>



