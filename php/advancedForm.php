<?php

// TODO: This can be deleted after search results refactor is complete

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
	
	$placesQuery = "SELECT np.name name, np.id id from NormalizedPlace np;";
	$peopleQuery = "SELECT np.name name, np.id id from NormalizedPerson np;";
			
	$findAuthor=mysqli_query( $connection, $authorQuery);
	$findRecipient=mysqli_query( $connection, $recipientQuery);
	$findFrom=mysqli_query( $connection, $fromQuery);
	$findTo=mysqli_query( $connection, $toQuery);
	$findYears=mysqli_query( $connection, $yearQuery);
	
	$findPeople=mysqli_query( $connection, $peopleQuery);
	$findPlaces=mysqli_query( $connection, $placesQuery);
		
	$numAuthors=mysqli_num_rows($findAuthor);
	$numRecipients=mysqli_num_rows($findRecipient);
	$numFrom=mysqli_num_rows($findFrom);
	$numTo=mysqli_num_rows($findTo);
	$numYears=mysqli_num_rows($findYears);
	
	$numPeople=mysqli_num_rows($findPeople);
	$numPlaces=mysqli_num_rows($findPlaces);
	
?>

<script>
	var placeIdToNames = {
	<?php
		$i=0;
		while ($i < $numPlaces) {
			$row = mysqli_fetch_array($findPlaces);
			$placeId = $row['id'];
			$placeName = json_encode($row['name']);
			echo "      $placeId: $placeName,\n";
			$i++;
		}
	?>	
	};
	var personIdToNames = {
	<?php
		$i=0;
		while ($i < $numPeople) {
			$row = mysqli_fetch_array($findPeople);
			$personId = $row['id'];
			$personName = json_encode($row['name']);
			echo "      $personId: $personName,\n";
			$i++;
		}
	?>	

	};
</script>

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
		<option value="7587" >Stephen F. Austin</option>
		<?php
		    if (array_key_exists('fromPersonId', $_GET)) {
		    	$fromPersonId = $_GET['fromPersonId'];
		    } else {
		        $fromPersonId = '';
		    } 
			$i=0;
			while ($i < $numAuthors) {
				$row = mysqli_fetch_array($findAuthor);
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
	<option value="7587" >Stephen F. Austin</option>
		<?php
		    if (array_key_exists('toPersonId', $_GET)) {
		    	$toPersonId = $_GET['toPersonId'];
		    } else {
		        $toPersonId = '';
		    } 
					$i=0;
			while ($i < $numRecipients) {
				$row = mysqli_fetch_array($findRecipient);
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
				$row = mysqli_fetch_array($findYears);
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
			$findYears=mysqli_query( $connection, $yearQuery);
		    if (array_key_exists('toYear', $_GET)) {
		    	$toYear = $_GET['toYear'];
		    } else {
		        $toYear = '';
		    } 
			$i=0;
			while ($i < $numYears) {
				$row = mysqli_fetch_array($findYears);
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
				$row = mysqli_fetch_array($findFrom);
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
				$row = mysqli_fetch_array($findTo);
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
	<br><br>
	sentiment: <select id="sentiment" name="sentiment" style="width:90px;">
		<option value="">any...</option>
		<?php
			$allSentiments = array();
			$allSentiments['positive'] = 'positive';
			$allSentiments['neutral'] = 'neutral';
			$allSentiments['negative'] = 'negative';
		
	     	if (array_key_exists('sentiment', $_GET)) {
	     		$selectedSentiment = $_GET['sentiment'];	
			} else {
				$selectedSentiment = '';
			}
			
			foreach($allSentiments as $thisSentiment) {
				$isSelected = '';
				
				if($selectedSentiment == $thisSentiment) {
					$isSelected = ' selected="selected"';
				}
				echo "<option value=\"$thisSentiment\" $isSelected>$thisSentiment</option>\n";
			}
			
			
		?>
	</select>
</div>
