<?php
include('php/database.php');
$connection = connectToDB();


function printNavTab($targetBrowseBy, $currentBrowseBy) {
	$navTitle = ucfirst($targetBrowseBy);
	if($targetBrowseBy == $currentBrowseBy) {
		echo $navTitle;
	} else {
		echo "<a href=\"?browseBy=${targetBrowseBy}\">{$navTitle}</a>";	
	} 
	
}

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
						d.summary summary
					FROM {$normalTable} np
					INNER JOIN Document d 
					ON np.id=d.{$joinColumn} 
					UNION
					SELECT 'Unclear' heading,
					d.id id,
					'zzzzz' surname,
					d.title title,
					d.summary summary
					from Document d
					where d.{$joinColumn} is null
					ORDER BY surname, heading";
	
}

function getNormalPlaceSql($joinColumn) {
	return 	   "SELECT np.name heading,
					d.id id,
					d.title title,
					d.summary summary,
					np.name order_key 
				FROM NormalizedPlace np
				INNER JOIN Document d 
				ON np.id=d.sentFromPlace
				UNION
				SELECT 'Undefined' heading,
					d.id id,
					d.title title,
					d.summary summary,
					'zzzzzzzzzz' order_key
				FROM Document d
				WHERE d.{$joinColumn} is null 
				ORDER BY order_key, title";
}


function getResult($targetBrowseBy) {
	$sqlStatements = 
		array('date' => 
				"SELECT left(creation,4) heading, id, title, summary from Document order by creation",
			'page' =>
				"SELECT concat('Page ', trim(leading '0' from right(id,4))) heading, id, title, summary from Document order by id",
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
	return mysql_query($sql);	
}



?>


<html class="browse">
	<header>
		<link rel="stylesheet" type="text/css" href="header.css" />
		<link rel="stylesheet" type="text/css" href="footer.css" />
		<link rel="stylesheet" type="text/css" href="style.css" /> <!-- Merged CSS -kmd -->
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
	</header>

	<body>
		<div id = "wrapper" class="shadow">
			<div id = "header">
				<?php include('header.php'); ?>
			</div>
			<!-- Created this page new -kmd -->
			<div class="content">
			<!-- I'm not sure we want this here -- what do you think, Karin?
			<h2>Browse</h2>
			
			<h3>Featured Searches</h3>
					<ul>
						<li><a href="results.php?textSearch=cholera">Cholera epidemic in the 1830s</a></li>
						<li><a href="results.php?recipient=Emily M Perry">Stephen Austin's Letters to his sister, Emily</a></li>
						<li><a href="results.php?textSearch=comanches">Problems with Comanches</a></li>
						<li><a href="results.php?to=New Orleans, Louisiana">Letters from Texas to New Orleans, LA</a></li>
					</ul>
			-->

			<h3>Browse All Documents</h3>

     <? if (array_key_exists('browseBy', $_GET)) {
    	$browseBy = $_GET['browseBy'];
    } else {
        $browseBy = 'date';
    } 
    echo $browseBy;
    ?>

			
			<!-- Add sort for whatever the metadata supports -->
			<p>Browse by: 
				<? printNavTab('date', $browseBy); ?>
				|
				<? printNavTab('author', $browseBy); ?>
				|
				<? printNavTab('recipient', $browseBy); ?>
				|
				<? printNavTab('origin', $browseBy); ?>
				|
				<? printNavTab('destination', $browseBy); ?>
				|
				<? printNavTab('page', $browseBy); ?>
			</p>

			



<div id="content">

	<h4>Actual content begins here</h4>
<?php
	$result = getResult($browseBy);
	$oldHeading = 'DEADBEEF';
    while ($row = mysql_fetch_array($result))
    {
        $heading = $row['heading'];
		$id = $row['id'];
		$title = $row['title'];
		$summary = $row['summary'];
		
		if($heading != $oldHeading) {
			# print the header
			print "<h5>{$heading}</h5>\n";
		}
		
		# I'd really prefer to put a BR between these two within the same paragraph, but that's not 
		# working for some reason
		print "<p><a href=\"document.php?id={$id}.xml\">{$title}</a></p>";		
		print "<p>{$summary}</p>";		
		
		
		$oldHeading=$heading;
		
    }
?>

      </div><!-- /#content -->
			
			
			</div><!-- /.content -->
			
			
			
			<div id = "footer">
				<?php include('footer.php'); ?>
			</div>
		</div>
	</body>
</html>