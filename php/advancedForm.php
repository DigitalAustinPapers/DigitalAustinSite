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

<div id="searchBox">
		<?php
	     	if (array_key_exists('query', $_GET)) {
		    	$query = $_GET['query'];
		    } else {
		        $query = '';
		    }
			echo "search: <input id=\"query\" type=\"text\" name=\"query\" size=\"30\" value=\"{$query}\"/>"	;
			
		?>
	<input type="submit" name="title" value="go"/>
	<br><br>
  			author: <select id="fromPersonId" name="fromPersonId" style="width:145px;">
		<option value="">any...</option>
		<?php
		    if (array_key_exists('fromPersonId', $_GET)) {
		    	$fromPersonId = $_GET['fromPersonId'];
		    } else {
		        $fromPersonId = '';
		    } 
			$i=0;
			while ($i < $numAuthors) {
				$row = mysql_fetch_array($findAuthor);
				$personId = $row['id'];
				$personName = $row['name'];
				$docFrequency = $row['frequency'];
				$isSelected = '';
				if($fromPersonId == $personId) {
					$isSelected=' selected="selected"';
				}
				
				echo "<option value=\"$personId\" $isSelected>$personName ($docFrequency letters)</option>\n";
				$i++;
			}
		?>
	</select>
	<!-- <br><br> -->
	recipient: <select id="toPersonId" name="toPersonId" style="width:150px;">
	<option value="">any...</option>
		<?php
		    if (array_key_exists('toPersonId', $_GET)) {
		    	$toPersonId = $_GET['toPersonId'];
		    } else {
		        $toPersonId = '';
		    } 
					$i=0;
			while ($i < $numRecipients) {
				$row = mysql_fetch_array($findRecipient);
				$personId = $row['id'];
				$personName = $row['name'];
				$docFrequency = $row['frequency'];
				$isSelected = '';
				if($toPersonId == $personId) {
					$isSelected=' selected="selected"';
				}
				echo "<option value=\"$personId\" $isSelected>$personName ($docFrequency letters)</option>\n";
				$i++;
			}
		?>
	</select>
	<br><br>
	from year: <select id="fromYear" name="fromYear" style="width:100px;">
	<option value="">any...</option>
		<?php
		    if (array_key_exists('fromYear', $_GET)) {
		    	$fromYear = $_GET['fromYear'];
		    } else {
		        $fromYear = '';
		    } 
			$i=0;
			while ($i < $numYears) {
				$row = mysql_fetch_array($findYears);
				$year = $row['year'];
				$docFrequency = $row['frequency'];
				$isSelected = '';
				if($fromYear == $year) {
					$isSelected=' selected="selected"';
				}
				echo "<option value=\"$year\" $isSelected>$year ($docFrequency letters)</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	<!-- <br><br> -->
	to year: <select id="toYear" name="toYear" style="width:85px;">
	<option value="">any...</option>
		<?php
			$findYears=mysql_query($yearQuery, $connection);
		    if (array_key_exists('toYear', $_GET)) {
		    	$toYear = $_GET['toYear'];
		    } else {
		        $toYear = '';
		    } 
			$i=0;
			while ($i < $numYears) {
				$row = mysql_fetch_array($findYears);
				$year = $row['year'];
				$docFrequency = $row['frequency'];
				$isSelected = '';
				if($toYear == $year) {
					$isSelected=' selected="selected"';
				}
				echo "<option value=\"$year\" $isSelected>$year ($docFrequency letters)</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	<br><br>
	sent from: <select id="fromPlaceId" name="fromPlaceId" style="width:100px;">
		<option value="">any...</option>
		<?php
	     	if (array_key_exists('fromPlaceId', $_GET)) {
		    	$fromPlaceId = $_GET['fromPlaceId'];
		    } else {
		        $fromPlaceId = '';
		    }
			$i=0;
			while ($i < $numFrom) {
				$row = mysql_fetch_array($findFrom);
				$placeId = $row['id'];
				$placeName = $row['name'];
				$docFrequency = $row['frequency'];
				$isSelected = '';
				if($fromPlaceId == $placeId) {
					$isSelected=' selected="selected"';
				}
				echo "<option value=\"$placeId\" $isSelected>$placeName ($docFrequency letters)</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	<!-- <br><br> -->
	sent to: <select id="toPlaceId" name="toPlaceId" style="width:90px;">
		<option value="">any...</option>
		<?php
	     	if (array_key_exists('toPlaceId', $_GET)) {
		    	$toPlaceId = $_GET['toPlaceId'];
		    } else {
		        $toPlaceId = '';
		    }
			$i=0;
			while ($i < $numTo) {
				$row = mysql_fetch_array($findTo);
				$placeId = $row['id'];
				$placeName = $row['name'];
				$docFrequency = $row['frequency'];
				$isSelected = '';
				if($toPlaceId == $placeId) {
					$isSelected=' selected="selected"';
				}
				echo "<option value=\"$placeId\" $isSelected>$placeName ($docFrequency letters)</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
</div>
