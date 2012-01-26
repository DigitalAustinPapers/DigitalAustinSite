<?php include('php/database.php'); ?>

<html>
	<head>
	</head>
	
	<?php $connection = connectToDB();
	
		// Retrieve all the data from the "example" table
		//$rows = mysql_query("SELECT idDocument, sent_from, sent_to FROM Document NATURAL JOIN Place")
		//$rows = mysql_query("SELECT DISTINCT sent_from FROM Document NATURAL JOIN Place ORDER BY sent_from")
		$rows = mysql_query("SELECT DISTINCT sent_to FROM Document NATURAL JOIN Place ORDER BY sent_to")
		or die(mysql_error());  

		while($row = mysql_fetch_array($rows)){
			//echo $row['idDocument'] . ": " . $row['sent_from'] . " - " . $row['sent_to'] . "<br/>";
			//echo $row['sent_from'] . "<br/>";
			echo $row['sent_to'] . "<br/>";
		} 

	?>
</html>