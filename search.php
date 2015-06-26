<?php
require_once 'src/TemplateRenderer.class.php';
include 'php/database.php';
$connection = connectToDB();

// Database queries

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

// Template variables
$placeIdToNames     = array();
$personIdToNames    = array();
$query              = '';
$fromPersonId       = '';
$fromPersonList     = array();
$toPersonId         = '';
$toPersonList       = array();
$fromYear           = '';
$toYear             = '';
$yearList           = array();
$fromPlaceId        = '';
$fromPlaceList      = array();
$toPlaceId          = '';
$toPlaceList        = array();
$selectedSentiment  = '';
$allSentiments      = array();

// Process GET parameters
if(array_key_exists('query', $_GET)) {
  $query = $_GET['query'];
}

if (array_key_exists('fromPersonId', $_GET)) {
  $fromPersonId = $_GET['fromPersonId'];
}

if (array_key_exists('toPersonId', $_GET)) {
  $toPersonId = $_GET['toPersonId'];
}

if (array_key_exists('fromYear', $_GET)) {
  $fromYear = $_GET['fromYear'];
}

if (array_key_exists('toYear', $_GET)) {
  $toYear = $_GET['toYear'];
}

if (array_key_exists('fromPlaceId', $_GET)) {
  $fromPlaceId = $_GET['fromPlaceId'];
}

if (array_key_exists('toPlaceId', $_GET)) {
  $toPlaceId = $_GET['toPlaceId'];
}

if (array_key_exists('sentiment', $_GET)) {
  $selectedSentiment = $_GET['sentiment'];
}

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

$template = new TemplateRenderer();
// Include any variables as an array in the second param
print $template->render('search.html.twig', array(
  'placeIdToNames'    => $placeIdToNames,
  'personIdToNames'   => $personIdToNames,
  'query'             => $query,
  'fromPersonId'      => $fromPersonId,
  'fromPersonList'    => $fromPersonList,
  'toPersonId'        => $toPersonId,
  'toPersonList'      => $toPersonList,
  'fromYear'          => $fromYear,
  'toYear'            => $toYear,
  'yearList'          => $yearList,
  'fromPlaceId'       => $fromPlaceId,
  'fromPlaceList'     => $fromPlaceList,
  'toPlaceId'         => $toPlaceId,
  'toPlaceList'       => $toPlaceList,
  'selectedSentiment' => $selectedSentiment,
  'allSentiments'     => $allSentiments,
));