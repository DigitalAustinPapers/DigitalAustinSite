<?php
	
	// Define the onLoad function
	echo "
		<script type='text/javascript'>
		var tl;
		function onLoad() {
			var eventSource = new Timeline.DefaultEventSource();\n";
	
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
			
			$result = mysql_query($query);
			
			$row = mysql_fetch_assoc($result);
			
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
	
	$result = mysql_query($query);
	$numRows = mysql_num_rows($result);
	
	for($i = 0; $i < $numRows; $i++){
		$row = mysql_fetch_assoc($result);
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
	
	$averageYear = 0;
	$resultCount = 0;
	
	// For each of the results
	while ($i < 1) {
		$j = 0;
		foreach($resultsArray as $id){
			$query = "SELECT idDocument, title, summary, creation FROM document NATURAL JOIN text WHERE idDocument = '$id'";
			$result = mysql_query($query);
			$row = mysql_fetch_assoc($result);
		
			// Display the title and create a link to the corresponding document
			$results = $results . "\n\t\t\t\t<p>" . ($j + 1) . ". " ."<a href='document.php?id=" . $row['idDocument'] .  "'>" . $row['title'] . "</a>: ";
			$results = $results . "<p>" . $row['summary'] . "</p></p><br>\n";
			
			// Add the body text to the $body variable for the body word cloud
			$query = "SELECT * FROM document NATURAL JOIN text WHERE idDocument = '" . $row['idDocument'] . "'";
			$result = mysql_query($query);
			$body_result = mysql_fetch_assoc($result);
			
			$body_text = str_replace("*p*", "<p>", $body_result["body"]);
			$body_text = str_replace("*/p*", "</p>", $body_text);
			$body_text = str_replace("*div1 type=\"body\"*", "<div1 type=\"body\">", $body_text);
			$body_text = str_replace("*div1 type=\"summary\"*", "<div1 type=\"summary\">", $body_text);
			$body_text = str_replace("*/div1*", "</div1>", $body_text);
			$body_text = str_replace("*person_mentioned*", "<person_mentioned>", $body_text);
			$body_text = str_replace("*/person_mentioned*", "</person_mentioned>", $body_text);
			$body_text = str_replace("*location_mentioned*", "</location_mentioned>", $body_text);
			$body_text = str_replace("*/location_mentioned*", "</location_mentioned>", $body_text);
			$body_text = str_replace("*date", "<date", $body_text);
			$body_text = str_replace("\"*", "<\">", $body_text);
			$body_text = str_replace("*/date_mentioned*", "</date_mentioned>", $body_text);
			
			$body .= " " . $body_text;
		
			// Add people to the $people variable for the people word cloud
			$query = "SELECT * FROM people WHERE person = '" . $row['idDocument'] . "'";
			$result = mysql_query($query);
			$people_result = mysql_fetch_assoc($result);
			
			$people_string = str_replace(" ", "_", $people_result["peopleList"]);
			$people_string = str_replace(",", " ", $people_string);
			
			$people .= " " . $people_string;
			
			// Add places to the $places variable for the places word cloud
			$query = "SELECT * FROM places WHERE place = '" . $row['idDocument'] . "'";
			$result = mysql_query($query);
			$places_result = mysql_fetch_assoc($result);
			
			$places_string = str_replace(" ", "_", $places_result["placesList"]);
			$places_string = str_replace(",", " ", $places_string);
			
			$places .= " " . $places_string;
			
			// Get date
			$matches = explode("-", $row['creation']);				
			$matches[1] = $matches[1] - 1;
			
			if ($matches[0] < 1840 && $matches[0] > 1819){
				$averageYear += $matches[0];
				$resultCount+=1;
			}
			
			$newSummary = str_replace(array("\r\n", "\r", "\n"), null, $row['summary']);
			$newSummary = str_replace("<p>", "", $newSummary);
			$newSummary = str_replace("</p>", "", $newSummary);
			$newSummary = str_replace("\"", "'", $newSummary);
			
			// Add the document as an event on the Simile Timeline
			$timelineData = $timelineData . "
			var eventDate$j = new Date();			
			eventDate$j.setFullYear($matches[0],$matches[1],$matches[2]);
			var myhash$j = new Object();
			myhash$j.start = eventDate$j;
			myhash$j.instant = true;
			myhash$j.text = \"" . $row['title'] . "\";
			myhash$j.caption = \"" . "click for more info" . "\";
			myhash$j.description = \"" . $newSummary . "\";
			var evt$j = new Timeline.DefaultEventSource.Event(myhash$j);
			eventSource.add(evt$j);\n";		
			
			$j++;
		}
		
		$i++;
	}
	
	// Find a point in the middle of the timeline
	$averageYear = (double)$averageYear/$resultCount;
	
	// Print the generated timeline events as javascript
	echo $timelineData;
	
	// Set the parameters for the timeline
	echo "
			var bandInfos = [
				Timeline.createBandInfo({
				eventSource:		eventSource,
				date:					'6/1/" . intval($averageYear) .  "',
				width:				'70%', 
				intervalUnit:		Timeline.DateTime.MONTH, 
				intervalPixels:	250
				}),
				
				Timeline.createBandInfo({
				overview:			true,
				eventSource:		eventSource,
				date:					'6/1/" . intval($averageYear) .  "',    
				width:				'30%', 
				intervalUnit:		Timeline.DateTime.YEAR, 
				intervalPixels:	100
				})
			];
			  
			bandInfos[1].syncWith = 0;
			bandInfos[1].highlight = true;
			  
			tl = Timeline.create(document.getElementById('my-timeline'), bandInfos);
		}
		
		var resizeTimerID = null;
		function onResize() {
			if (resizeTimerID == null) {
				resizeTimerID = window.setTimeout(
					function() {
						resizeTimerID = null;
						tl.layout();
					}, 500
				);
			}
		}
		
		window.onload = function(){
			// Word Cloud Code
			// WRITTEN BY DEL
			// lotsofcode.com
			
			// Get the elements of the main 'cloud' form
			e = document.forms['cloud'].elements;
			// Get the length of all the elements
			l = e.length;
			for (var i = 0; i < l; i++) {
				// If the element is a textarea
				if (e[i].type == 'textarea') {
					// select the element as we click on it
					e[i].onclick = e[i].select;
				}
			}
		}
		</script>";
	
	mysql_close($connection);
?>

	<!--
	<form name='myform' action='test.php' method='POST'>
	<input type="hidden" name='places' value='<?php print_r(str_replace("'", "*", serialize($place))); ?>;' />		
	<input type='submit' name='map' value='map'>
	</form> -->