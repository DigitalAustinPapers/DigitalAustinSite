<?php


function buildSearchQuery($orderBy, $groupBy) {
	//Stem and split the query
	$stemmer = new PorterStemmer();
	$text = trim(strtolower($_GET['query']));
	//Split on non-word characters
	
	$stemCondition = "1=1";
	$stemFrom = "";
	
	if("" != $text) {
		$stemCondition = " stem in (NULL";
		$stemFrom = "  INNER JOIN StemCount ON StemCount.docId = Document.id";
		$words = preg_split('/[\W]+/', $text);
		//Stem each word
		
		$stems = array_map(array($stemmer, 'Stem'), $words);
		$stemCounts = array_count_values($stems);
		
		$stemCondition = " stem in (NULL";
		foreach ($stemCounts as $stem => $count)
		{
		    if (strlen($stem) != 0) {
		        $escapedStem = mysql_real_escape_string($stem);
		        $stemCondition .= ", '$escapedStem'";
		    }
		}
		$stemCondition .= ')';
		
	}
	
	$locationCondition = '';
	if (array_key_exists('location', $_GET) && ($_GET['location'] != -1))
	{
	    $escapedLocation = mysql_real_escape_string($_GET['location']);
	    $locationCondition = " AND (sentToPlace=$escapedLocation
	        OR sentFromPlace=$escapedLocation) ";
	}
	
	
	
	$fromDateCondition = '';
	if (array_key_exists('fromYear', $_GET) && ($_GET['fromYear'] != ''))
	{
	    $escapedFromYear = mysql_real_escape_string($_GET['fromYear']);
	    $fromDateCondition = " AND creation > '$escapedFromYear' ";
	}
	
	$toDateCondition = '';
	if (array_key_exists('toYear', $_GET) && ($_GET['toYear'] != ''))
	{
	    $escapedToYear = mysql_real_escape_string($_GET['toYear']);
		$escapedToYear = $escapedToYear + 1; # date 
		
	    $toDateCondition = " AND creation < '$escapedToYear' ";
	}
	
	
	
	$fromPersonCondition = '';
	if (array_key_exists('fromPersonId', $_GET) && ($_GET['fromPersonId'] != ''))
	{
	    $escapedFromPersonId = mysql_real_escape_string($_GET['fromPersonId']);
	    $fromPersonCondition = " AND sentFromPerson = $escapedFromPersonId ";
	}
	
	$toPersonCondition = '';
	if (array_key_exists('toPersonId', $_GET) && ($_GET['toPersonId'] != ''))
	{
	    $escapedToPersonId = mysql_real_escape_string($_GET['toPersonId']);
	    $toPersonCondition = " AND sentToPerson = $escapedToPersonId ";
	}
	
	
	
	
	$fromPlaceCondition = '';
	if (array_key_exists('fromPlaceId', $_GET) && ($_GET['fromPlaceId'] != ''))
	{
	    $escapedFromPlaceId = mysql_real_escape_string($_GET['fromPlaceId']);
	    $fromPlaceCondition = " AND sentFromPlace = $escapedFromPlaceId ";
	}
	
	$toPlaceCondition = '';
	if (array_key_exists('toPlaceId', $_GET) && ($_GET['toPlaceId'] != ''))
	{
	    $escapedToPlaceId = mysql_real_escape_string($_GET['toPlaceId']);
	    $toPlaceCondition = " AND sentToPlace = $escapedToPlaceId ";
	}
	
	
	
	$sql = "
	    FROM Document
	    $stemFrom
	    LEFT OUTER JOIN NormalizedPlace AS Destination
	        ON Document.sentToPlace = Destination.id
	    LEFT OUTER JOIN NormalizedPlace AS Source
	        ON Document.sentFromPlace = Source.id
	    WHERE
	    $stemCondition
	    $locationCondition
	    $fromDateCondition
	    $toDateCondition
	    $fromPersonCondition
	    $toPersonCondition
	    $fromPlaceCondition
	    $toPlaceCondition
        $groupBy
	    $orderBy 
	";
	
	
	return $sql;	
	
}


function buildDocumentSearchQuery() {
	$text = trim(strtolower($_GET['query']));

	$stemSimilarity = "1";
	if("" != $text) {
		$stemSimilarity = "sum(StemCount.tfIdf) / Document.vectorLength";
	}
	$select = "
	    SELECT Document.id as id, Document.summary as summary,
	        Document.title as title, Document.creation as date,
	        $stemSimilarity as similarity,
	        Destination.lat as dstLat, Destination.lng as dstLng,
	        Source.lat as srcLat, Source.lng as srcLng";

	$orderBy = '';
	if (array_key_exists('sort', $_GET)) {
	    if ($_GET['sort'] === 'similarity') {
	        $orderBy = ' ORDER BY similarity ';
	    }
	    elseif ($_GET['sort'] === 'date') {
	        $orderBy = ' ORDER BY date ';
	    }
	}
	
	$groupBy = "GROUP BY Document.Id";

    $sql = $select . buildSearchQuery($orderBy, $groupBy);
	logString($sql);
	return $sql;
}


function buildPersonCloudSearchQuery() {//Text Cloud

	$select = "SELECT Document.id as document_id";
	$groupBy = "GROUP BY Document.id";
	$orderBy = "";	
	$innerSql = $select . buildSearchQuery($orderBy, $groupBy);
	
	
	$sql = "
		SELECT trim(pr.text) as text,
			count(*) as weight
		FROM
			PersonReference pr
		INNER JOIN
			( {$innerSql} ) docs
			ON pr.docId = docs.document_id 
		GROUP BY text
		ORDER BY weight DESC
		LIMIT 50;
	";
	
	
	logString($sql);
	return $sql;	
}



function buildWordCloudSearchQuery() {//Text Cloud

	$select = "SELECT Document.id as document_id";
	$groupBy = "GROUP BY Document.id";
	$orderBy = "
	    ORDER BY document_id DESC
	    LIMIT 50
		";	
	$innerSql = $select . buildSearchQuery($orderBy, $groupBy);
	
	
	$sql = "
		SELECT wc.word as text,
			sum(wc.count) as weight
		FROM
			WordCount wc
		INNER JOIN
			( {$innerSql} ) docs
			ON wc.docId = docs.document_id 
		WHERE wc.word NOT IN (SELECT word FROM StopWords)
		GROUP BY text
		ORDER BY weight DESC
		LIMIT 50;
	";
	
	
	logString($sql);
	return $sql;	
}




?>