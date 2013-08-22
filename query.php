<?php include('php/database.php'); ?>

<html>
	<head>
	</head>
	
	<?php $connection = connectToDB();
	
		// Retrieve all the data from the "example" table
		//$rows = mysql_query("SELECT idDocument, sent_from, sent_to FROM Document NATURAL JOIN Place")
		//$rows = mysql_query("SELECT DISTINCT sent_from FROM Document NATURAL JOIN Place ORDER BY sent_from")
		$rows = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT DISTINCT sent_to FROM Document NATURAL JOIN Place ORDER BY sent_to")
		or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));  

		while($row = mysqli_fetch_array($rows)){
			//echo $row['idDocument'] . ": " . $row['sent_from'] . " - " . $row['sent_to'] . "<br/>";
			//echo $row['sent_from'] . "<br/>";
			echo $row['sent_to'] . "<br/>";
		} 

	?>
</html>