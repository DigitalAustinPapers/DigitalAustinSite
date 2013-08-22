<?php
	// Initialize variables
	$results = "";
	$body = "";
	$people = "";
	$places = "";
	$timelineData = "";
	
	$textSearch = "";
	$author = "";
	$recipient = "";
	$fromYear = "";
	$toYear = "";
	$from = "";
	$to = "";
	
	if(!isset($_POST['textSearch']) || $_POST['textSearch'] == null){
		$textSearch = $_GET['textSearch'];
	}
	else{
		$textSearch = $_POST['textSearch'];
	}
	if(!isset($_POST['author']) || $_POST['author'] == null){
		$author = $_GET['author'];
	}
	else{
		$author = $_POST['author'];
	}
	if(!isset($_POST['recipient']) || $_POST['recipient'] == null){
		$recipient = $_GET['recipient'];
	}
	else{
		$recipient = $_POST['recipient'];
	}
	if(!isset($_POST['fromYear']) || $_POST['fromYear'] == null){
		$fromYear = $_GET['fromYear'];
	}
	else{
		$fromYear = $_POST['fromYear'];
	}
	if(!isset($_POST['toYear']) || $_POST['toYear'] == null){
		$toYear = $_GET['toYear'];
	}
	else{
		$toYear = $_POST['toYear'];
	}
	if(!isset($_POST['from']) || $_POST['from'] == null){
		$from = $_GET['from'];
	}
	else{
		$from = $_POST['from'];
	}
	if(!isset($_POST['to']) || $_POST['to'] == null){
		$to = $_GET['to'];
	}
	else{
		$to = $_POST['to'];
	}
	
	$resultsArray = array();
	
	// if there are search terms
	if(strlen($textSearch) > 0){
	
		// search terms
		$searchArray = explode(" ", $textSearch);
	
		// get results for each term
		foreach($searchArray as $term){

			$query = "SELECT documentList FROM search_index WHERE word = '$term'";
			
			$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
			
			$row = mysqli_fetch_assoc($result);
			
			$resultsList = $row['documentList'];	
			$tempArray = explode(",", $resultsList);
			
			// find common documents
			if(count($resultsArray) > 0){
				$resultsArray = array_intersect($tempArray, $resultsArray);
			}
			else{
				$resultsArray = $tempArray;
			}
		}
	}
	
	// get all documents that match the advanced search filters
	// define the query
	$query = "SELECT DISTINCT idDocument FROM document";
	
	// If there were no search terms or filters, yell at the user
	if(strlen($textSearch) == 0 && strlen($author) == 0 && strlen($recipient) == 0 && strlen($fromYear) == 0 && strlen($toYear) == 0 && strlen($from) == 0 && strlen($to) == 0){
		$results = $results . "<p>Enter search terms and/or filters</p>";
	}
	
	// Add to the query as needed, depending on selected filters
	else{
		if(strlen($author) != 0 || strlen($recipient) != 0 || strlen($fromYear) != 0 || strlen($toYear) != 0 || strlen($from) != 0 || strlen($to) != 0){
			$query = $query . " WHERE";
		}
		// Author filter
		if(strlen($author) != 0){
			$query = $query . " author LIKE '%$author%'";
			if(strlen($recipient) != 0 || strlen($fromYear) != 0 || strlen($toYear) != 0 || strlen($from) != 0 || strlen($to) != 0){
				$query = $query . " &&";
			}
		}
		// Recipient filter
		if(strlen($recipient) != 0){
			$query = $query . " recipient LIKE '%$recipient%'";
			if(strlen($fromYear) != 0 || strlen($toYear) != 0 || strlen($from) != 0 || strlen($to) != 0){
				$query = $query . " &&";
			}
		}
		// Date range
		if(strlen($fromYear) != 0 || strlen($toYear) != 0){
			if(strlen($fromYear) == 0){
				$fromYear = "1800";
			}
			if(strlen($toYear) == 0){
				$toYear = "1900";
			}
			$fromYear = $fromYear . "-00-00";
			$toYear = $toYear . "-00-00";
			$query = $query . " creation between '$fromYear' AND '$toYear'";
			if(strlen($from) != 0 || strlen($to) != 0){
				$query = $query . " &&";
			}
		}
		// Sent-from filter
		if(strlen($from) != 0){
			$query = $query . " sent_from = '$from'";
			if(strlen($to) != 0){
				$query = $query . " &&";
			}
		}
		// Sent-to filter
		if(strlen($to) != 0){
			$query = $query . " sent_to = '$to'";
		}
	}
	
	$result = mysqli_query($GLOBALS["___mysqli_ston"], $query);
	$numRows = mysqli_num_rows($result);
	
	for($i = 0; $i < $numRows; $i++){
		$row = mysqli_fetch_assoc($result);
		$filtersArray[$i] = $row["idDocument"];
	}
	
	// merge the text search results with the filter results
	if(strlen($textSearch) > 0){
		$resultsArray = array_intersect($resultsArray, $filtersArray);
	}
	else{
		$resultsArray = $filtersArray;
	}
	
	// remove duplicate results
	$resultsArray = array_unique($resultsArray);
	// get number of results
	$numResults = count($resultsArray);
	
	$i = 0;
	
	// Display number of results, and search terms (when applicable)
	$results = $results . "<p>Showing $numResults results";
	if(strlen($textSearch) > 0){
		$results = $results . " for \"$textSearch\"</p>";
	}
	else{
		$results = $results . "</p>";	
	}	
	
	$resultCount = 0;
	
	echo "
		var latlng = new google.maps.LatLng(30.30, -97.70);
		var myOptions = {
		  zoom: 8,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.TERRAIN
		};
		var map = new google.maps.Map(document.getElementById('map_canvas'),
			myOptions);
		
		var myLatLng;
		var bounds = new google.maps.LatLngBounds();\n";

	// For each of the results
	while ($i < $numResults) {
	
		$row = mysqli_fetch_assoc($result);
		
		$placeResult = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT normalized_to, lat, long FROM  Place, Coordinates WHERE Place INNER JOIN Coordinates ON Place.normalized_to=Coordinates.idCity WHERE idDocument = '" . $row["idDocument"] . "'");
		//$placeResult = mysql_query("SELECT normalized_to FROM Place WHERE idDocument = '" . $row["idDocument"] . "'");
		//$placeResult2 = mysql_query("SELECT normalized_from FROM Place WHERE idDocument = '" . $row["idDocument"] . "'");
		
		$placeRow = mysqli_fetch_assoc($placeResult);
		//$placeRow = mysql_fetch_array($placeResult);
		//$placeRow2 = mysql_fetch_array($placeResult2);
		//$place[] = $placeRow[0];
		//$place2[] = $placeRow2[0];

		echo "myLatLng = new google.maps.LatLng( $placeRow['lat'], $placeRow['long'] );\n";
		echo "var marker = new google.maps.Marker({\n";
		echo "title: 'SAN FELIPE, TEXAS',\n";
		echo "position: myLatLng,\n"; 
		echo "map: map,\n";
		echo "icon: 'pics/gmap_blue_icon.png'\n";
		echo "});\n";
		echo "bounds.extend(myLatLng)\n";
		echo "map.fitBounds(bounds)\n";

		$i++;
	}

	//include('php/displaymap.php');
	
?>
