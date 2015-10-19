<?php
require_once 'src/TemplateRenderer.class.php';
include('php/wordcloud.class.php');
include_once 'php/database.php';
$connection = connectToDB();

// Database queries

$totalSql = "SELECT COUNT(*) FROM Document";

$docDistSql = "SELECT YEAR(creation) doc_year, COUNT(*) doc_total FROM Document GROUP BY doc_year ORDER BY doc_year";

$authorQuery = "SELECT np.name name, np.id id,
                if(
                  RIGHT(np.name, LOCATE(' ', REVERSE(np.name)) - 1) <>'',
                  RIGHT(np.name, LOCATE(' ', REVERSE(np.name)) - 1), np.name)
                surname, count(*) frequency
                FROM NormalizedPerson np
                INNER JOIN Document d
                ON np.id=d.sentFromPerson
                GROUP BY id, name, surname
                ORDER BY surname";

$recipientQuery = "SELECT np.name name, np.id id,
                  if(
                    RIGHT(np.name, LOCATE(' ', REVERSE(np.name)) - 1)	<>'',
                    RIGHT(np.name, LOCATE(' ', REVERSE(np.name)) - 1), np.name)
                  surname, count(*) frequency
                  FROM NormalizedPerson np
                  INNER JOIN Document d
                  ON np.id=d.sentToPerson
                  GROUP BY id, name, surname
                  ORDER BY surname";

$fromQuery = "SELECT np.name name, np.id id, count(*) frequency
              FROM NormalizedPlace np
              INNER JOIN Document d
              ON np.id=d.sentFromPlace
              GROUP BY id, name
              ORDER BY name";

$toQuery = "SELECT np.name name, np.id id, count(*) frequency
            FROM NormalizedPlace np
            INNER JOIN Document d
            ON np.id=d.sentToPlace
            GROUP BY id, name
            ORDER BY name";

$yearQuery = "SELECT left(creation,4) year, count(*) frequency
              FROM Document
              WHERE creation > '1'
              GROUP BY year
              ORDER BY 1";

$placesQuery = "SELECT np.name name, np.id id
                FROM NormalizedPlace np;";

$peopleQuery = "SELECT np.name name, np.id id
                FROM NormalizedPerson np;";

$totalResult = mysqli_query($connection, $totalSql);
$totalDocs   = mysqli_result($totalResult, 0);
logString($totalDocs);

$docDistResult = mysqli_query($connection, $docDistSql);

$findAuthor     = mysqli_query( $connection, $authorQuery);
$findRecipient  = mysqli_query( $connection, $recipientQuery);
$findFrom       = mysqli_query( $connection, $fromQuery);
$findTo         = mysqli_query( $connection, $toQuery);
$findYears      = mysqli_query( $connection, $yearQuery);
$findPeople     = mysqli_query( $connection, $peopleQuery);
$findPlaces     = mysqli_query( $connection, $placesQuery);

$numAuthors     = mysqli_num_rows($findAuthor);
$numRecipients  = mysqli_num_rows($findRecipient);
$numFrom        = mysqli_num_rows($findFrom);
$numTo          = mysqli_num_rows($findTo);
$numYears       = mysqli_num_rows($findYears);
$numPeople      = mysqli_num_rows($findPeople);
$numPlaces      = mysqli_num_rows($findPlaces);

// GET parameters array
$search_params = array();

// Process GET parameters
if(array_key_exists('query', $_GET)) {
  $search_params['query'] = $_GET['query'];
}

if (array_key_exists('fromPersonId', $_GET)) {
  $search_params['fromPersonId'] = $_GET['fromPersonId'];
}

if (array_key_exists('toPersonId', $_GET)) {
  $search_params['toPersonId'] = $_GET['toPersonId'];
}

if (array_key_exists('fromYear', $_GET)) {
  $search_params['fromYear'] = $_GET['fromYear'];
}

if (array_key_exists('toYear', $_GET)) {
  $search_params['toYear'] = $_GET['toYear'];
}

if (array_key_exists('fromPlaceId', $_GET)) {
  $search_params['fromPlaceId'] = $_GET['fromPlaceId'];
}

if (array_key_exists('toPlaceId', $_GET)) {
  $search_params['toPlaceId'] = $_GET['toPlaceId'];
}

if (array_key_exists('sentiment', $_GET)) {
  $search_params['sentiment'] = $_GET['sentiment'];
}

// Determine total number of documents in database
$docDistDocs   = array();
while($docDistRow = $docDistResult->fetch_assoc()) {
  $docDistDocs[$docDistRow['doc_year']] = $docDistRow['doc_total'];
}
$docDistDocs = json_encode($docDistDocs);

// Search template dropdown options
$search_dropdowns = array();
$placeIdToNames   = array();
$personIdToNames  = array();
$fromPersonList   = array();
$toPersonList     = array();
$yearList         = array();
$fromPlaceList    = array();
$toPlaceList      = array();
$allSentiments    = array();

// Generate $placeIdToNames JSON for template
$i=0;
while ($i < $numPlaces) {
  $row = mysqli_fetch_array($findPlaces);
  $placeId = $row['id'];
  $placeName = $row['name'];
  $placeIdToNames[$placeId] = $placeName;
  $i++;
}
$placeIdToNames = json_encode($placeIdToNames);

// Generate $personIdToNames JSON for template
$i=0;
while ($i < $numPeople) {
  $row = mysqli_fetch_array($findPeople);
  $personId = $row['id'];
  $personName = $row['name'];
  $personIdToNames[$personId] = $personName;
  $i++;
}
$personIdToNames = json_encode($personIdToNames);

// Function to generate list of authors and recipients
function listPersons($numRows, $queryName) {
  $personList = array();
  $i=0;
  while ($i < $numRows) {
    $row = mysqli_fetch_array($queryName);
    $personId = $row['id'];
    $personName = $row['name'];
    $docFrequency = $row['frequency'];
    $personList[$personId] = array(
      'person_name'   => $personName,
      'doc_frequency' => $docFrequency,
    );
    $i++;
  }
  return $personList;
}
// Generate the lists
$fromPersonList = listPersons($numAuthors, $findAuthor);
$toPersonList   = listPersons($numRecipients, $findRecipient);

// Generate list of years
$i=0;
while ($i < $numYears) {
  $row = mysqli_fetch_array($findYears);
  $year = $row['year'];
  $docFrequency = $row['frequency'];
  $yearList[$year] = $docFrequency;
  $i++;
}

// Function to generate list of places
function listPlaces($numRows, $queryName) {
  $placeList = array();
  $i=0;
  while ($i < $numRows) {
    $row = mysqli_fetch_array($queryName);
    $placeId = $row['id'];
    $placeName = $row['name'];
    $docFrequency = $row['frequency'];
    $placeList[$placeId] = array(
      'place_name'   => $placeName,
      'doc_frequency' => $docFrequency,
    );
    $i++;
  }
  return $placeList;
}
// Generate place lists
$fromPlaceList = listPlaces($numFrom, $findFrom);
$toPlaceList   = listPlaces($numTo, $findTo);

// Populate sentiments array
$allSentiments['positive'] = 'positive';
$allSentiments['neutral']  = 'neutral';
$allSentiments['negative'] = 'negative';

$search_dropdowns = array(
  'placeIdToNames'    => $placeIdToNames,
  'personIdToNames'   => $personIdToNames,
  'fromPersonList'    => $fromPersonList,
  'toPersonList'      => $toPersonList,
  'yearList'          => $yearList,
  'fromPlaceList'     => $fromPlaceList,
  'toPlaceList'       => $toPlaceList,
  'allSentiments'     => $allSentiments,
);

$template = new TemplateRenderer();
// Include any variables as an array in the second param
print $template->render('search.html.twig', array(
                        'search_params'        => $search_params,
                        'search_dropdowns'     => $search_dropdowns,
                        'totalDocsCount'       => $totalDocs,
                        'totalDocDistribution' => $docDistDocs,
                        'body_id'               => 'search'
));