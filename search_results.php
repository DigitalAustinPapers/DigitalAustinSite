<?php
// TODO: This needs to be moved out of the document root

// Most of this logic was previously in /data/search.php. The original
// intent was to include search_results.php in /data/search.php. That
// proved difficult because of include paths.

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

require_once 'src/TemplateRenderer.class.php';
include 'php/porterStemmer.php';
include_once 'php/database.php';
include 'data/query.php';
logString("search.php start");

$database = connectToDB();

$sql = buildDocumentSearchQuery();

$docData = array();
$result = mysqli_query($GLOBALS["___mysqli_ston"], $sql) or die($sql . "<br>" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
while ($row = mysqli_fetch_assoc($result))
{
  array_push($docData, $row);
}
//print json_encode($docData);

logString("search.php end");

$search_results = array();

$template = new TemplateRenderer();

foreach ($docData as $result_key => $result_value) {
  $rendered_template = $template->render('_search_result.twig', array(
    'result' => $result_value,
  ));
  array_push($search_results, $rendered_template);
}

print json_encode(array('json'=> $docData,
                        'html'=> $search_results));