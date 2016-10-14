<?php require_once 'src/TemplateRenderer.class.php';

include('php/database.php');
$connection = connectToDB();

function getNormalSql($normalTable, $joinColumn) {
	return		"SELECT np.name heading, 
						d.id id,
						if(RIGHT(np.name, 
								LOCATE(' ', REVERSE(np.name)) - 1)
							<>'',
							RIGHT(np.name, 
								LOCATE(' ', REVERSE(np.name)) - 1) , 
								np.name) surname,
						d.title title,
						d.summary summary,
						d.creation creation
					FROM {$normalTable} np
					INNER JOIN Document d 
					ON np.id=d.{$joinColumn} 
					UNION
					SELECT 'Unclear' heading,
					d.id id,
					'zzzzz' surname,
					d.title title,
					d.summary summary,
					d.creation creation
					from Document d
					where d.{$joinColumn} is null
					ORDER BY surname, heading";
	
}

function getNormalPlaceSql($joinColumn) {
	return 	   "SELECT np.name heading,
					d.id id,
					d.title title,
					d.summary summary,
					np.name order_key,
					d.creation creation
				FROM NormalizedPlace np
				INNER JOIN Document d 
				ON np.id=d.sentFromPlace
				UNION
				SELECT 'Undefined' heading,
					d.id id,
					d.title title,
					d.summary summary,
					'zzzzzzzzzz' order_key,
					d.creation creation
				FROM Document d
				WHERE d.{$joinColumn} is null 
				ORDER BY order_key, title";
}


function getResult($targetBrowseBy) {
	$sqlStatements = 
		array('date' => 
				"SELECT left(creation,4) heading, creation, id, title, summary from Document order by creation",
			'page' =>
				"SELECT concat('Page ', trim(leading '0' from right(id,4))) heading, creation, id, title, summary from Document order by id",
			'author' =>
				getNormalSql('NormalizedPerson', 'sentFromPerson'),
			'recipient' =>
				getNormalSql('NormalizedPerson', 'sentToPerson'),
			'origin' =>
				getNormalPlaceSql('sentFromPlace'),
			'destination' =>
				getNormalPlaceSql('sentToPlace')								
			);



	$sql = $sqlStatements[$targetBrowseBy];
	logString($sql);
	return mysqli_query($GLOBALS["___mysqli_ston"], $sql);	
}

// $browseBy is sent to the template
if (array_key_exists('browseBy', $_GET)) {
  $browseBy = $_GET['browseBy'];
} else {
    $browseBy = 'date';
}

$result = getResult($browseBy);
$oldHeading = 'DEADBEEF';
// $results_list is sent to the template
$results_list = array();
while ($row = mysqli_fetch_array($result))
{
  $heading = $row['heading'];
  $id = $row['id'];
  $title = $row['title'];
  $summary = $row['summary'];
	$date = $row['creation'];

  if($heading != $oldHeading) {
    # print the header
    if($heading=='0000') {
      $cleanHeading = 'Undated';
    } else {
      $cleanHeading = $heading;
    }
  }
  $results_list[$cleanHeading][$id] = array(
    'document_title' => $title,
    'document_summary' => $summary,
		'document_date' => $date);

  $oldHeading=$heading;
}

// $sortCategories is sent to the template
$sortCategories = array('date', 'author', 'origin',
  'recipient', 'destination');

$template = new TemplateRenderer();
// Include any variables as an array in the second param
print $template->render('browse.html.twig', array(
  'browseBy'        => $browseBy,
  'sortCategories'  => $sortCategories,
  'results'         => $results_list,
  'body_id' => 'browse'
));