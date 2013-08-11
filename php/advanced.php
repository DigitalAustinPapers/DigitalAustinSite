<?php

	$authorQuery = "SELECT np.name name, 
						np.id id,  
						if(RIGHT(np.name, 
								LOCATE(' ', REVERSE(np.name)) - 1)
							<>'',
							RIGHT(np.name, 
								LOCATE(' ', REVERSE(np.name)) - 1) , 
								np.name) surname,
						count(*) frequency 
					FROM NormalizedPerson np
					INNER JOIN Document d 
					ON np.id=d.sentFromPerson 
					GROUP BY id, name, surname 
					ORDER BY surname";
	$recipientQuery = "SELECT np.name name, 
						np.id id,  
						if(RIGHT(np.name, 
								LOCATE(' ', REVERSE(np.name)) - 1)
							<>'',
							RIGHT(np.name, 
								LOCATE(' ', REVERSE(np.name)) - 1) , 
								np.name) surname,
						count(*) frequency 
					FROM NormalizedPerson np
					INNER JOIN Document d 
					ON np.id=d.sentToPerson 
					GROUP BY id, name, surname 
					ORDER BY surname";
	$fromQuery = "SELECT np.name name, 
						np.id id,  
						count(*) frequency 
					FROM NormalizedPlace np
					INNER JOIN Document d 
					ON np.id=d.sentFromPlace 
					GROUP BY id, name 
					ORDER BY name";
	$toQuery = "SELECT np.name name, 
						np.id id,  
						count(*) frequency 
					FROM NormalizedPlace np
					INNER JOIN Document d 
					ON np.id=d.sentToPlace 
					GROUP BY id, name 
					ORDER BY name";
	$yearQuery = "SELECT left(creation,4) year, count(*) frequency FROM Document WHERE creation > '1' GROUP BY year ORDER BY 1";
		
	$findAuthor=mysql_query($authorQuery, $connection);
	$findRecipient=mysql_query($recipientQuery, $connection);
	$findFrom=mysql_query($fromQuery, $connection);
	$findTo=mysql_query($toQuery, $connection);
	$findYears=mysql_query($yearQuery, $connection);
	
	$numAuthors=mysql_numrows($findAuthor);
	$numRecipients=mysql_numrows($findRecipient);
	$numFrom=mysql_numrows($findFrom);
	$numTo=mysql_numrows($findTo);
	$numYears=mysql_numrows($findYears);

?>

<form action="results.php" method="get">
<div id="searchBox">
	search: <input type="text" name="query" size="30"/>
	<input type="submit" name="title" value="go"/>
	<br><br>
  			author: <select name="fromPersonId" style="width:145px;">
		<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numAuthors) {
				$row = mysql_fetch_array($findAuthor);
				$personId = $row['id'];
				$personName = $row['name'];
				$docFrequency = $row['frequency'];
				
				echo "<option value=\"$personId\">$personName ($docFrequency letters)</option>\n";
				$i++;
			}
		?>
	</select>
	<!-- <br><br> -->
	recipient: <select name="toPersonId" style="width:150px;">
	<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numRecipients) {
				$row = mysql_fetch_array($findRecipient);
				$personId = $row['id'];
				$personName = $row['name'];
				$docFrequency = $row['frequency'];
				echo "<option value=\"$personId\">$personName ($docFrequency letters)</option>\n";
				$i++;
			}
		?>
	</select>
	<br><br>
	from year: <select name="fromYear" style="width:100px;">
	<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numYears) {
				$row = mysql_fetch_array($findYears);
				$year = $row['year'];
				$docFrequency = $row['frequency'];
				echo "<option value=\"$year\">$year ($docFrequency letters)</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	<!-- <br><br> -->
	to year: <select name="toYear" style="width:85px;">
	<option value="">any...</option>
		<?php
			$findYears=mysql_query($yearQuery, $connection);
			$i=0;
			while ($i < $numYears) {
				$row = mysql_fetch_array($findYears);
				$year = $row['year'];
				$docFrequency = $row['frequency'];
				echo "<option value=\"$year\">$year ($docFrequency letters)</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	<br><br>
	sent from: <select name="fromPlaceId" style="width:100px;">
		<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numFrom) {
				$row = mysql_fetch_array($findFrom);
				$placeId = $row['id'];
				$placeName = $row['name'];
				$docFrequency = $row['frequency'];
				echo "<option value=\"$placeId\">$placeName ($docFrequency letters)</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	<!-- <br><br> -->
	sent to: <select name="toPlaceId" style="width:90px;">
		<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numTo) {
				$row = mysql_fetch_array($findTo);
				$placeId = $row['id'];
				$placeName = $row['name'];
				$docFrequency = $row['frequency'];
				echo "<option value=\"$placeId\">$placeName ($docFrequency letters)</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	</div>
</form>