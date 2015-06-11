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
		        $escapedStem = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $stem) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		        $stemCondition .= ", '$escapedStem'";
		    }
		}
		$stemCondition .= ')';
		
	}
	
	$locationCondition = '';
	if (array_key_exists('location', $_GET) && ($_GET['location'] != -1))
	{
	    $escapedLocation = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['location']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	    $locationCondition = " AND (sentToPlace=$escapedLocation
	        OR sentFromPlace=$escapedLocation) ";
	}
	
	
	
	$fromDateCondition = '';
	if (array_key_exists('fromYear', $_GET) && ($_GET['fromYear'] != ''))
	{
	    $escapedFromYear = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['fromYear']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	    $fromDateCondition = " AND creation > '$escapedFromYear' ";
	}
	
	$toDateCondition = '';
	if (array_key_exists('toYear', $_GET) && ($_GET['toYear'] != ''))
	{
	    $escapedToYear = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['toYear']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
		$escapedToYear = $escapedToYear + 1; # date 
		
	    $toDateCondition = " AND creation < '$escapedToYear' ";
	}
	
	
	
	$fromPersonCondition = '';
	if (array_key_exists('fromPersonId', $_GET) && ($_GET['fromPersonId'] != ''))
	{
	    $escapedFromPersonId = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['fromPersonId']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	    $fromPersonCondition = " AND sentFromPerson = $escapedFromPersonId ";
	}
	
	$toPersonCondition = '';
	if (array_key_exists('toPersonId', $_GET) && ($_GET['toPersonId'] != ''))
	{
	    $escapedToPersonId = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['toPersonId']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	    $toPersonCondition = " AND sentToPerson = $escapedToPersonId ";
	}
	
	
	
	
	$fromPlaceCondition = '';
	if (array_key_exists('fromPlaceId', $_GET) && ($_GET['fromPlaceId'] != ''))
	{
	    $escapedFromPlaceId = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['fromPlaceId']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	    $fromPlaceCondition = " AND sentFromPlace = $escapedFromPlaceId ";
	}
	
	$toPlaceCondition = '';
	if (array_key_exists('toPlaceId', $_GET) && ($_GET['toPlaceId'] != ''))
	{
	    $escapedToPlaceId = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_GET['toPlaceId']) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
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
	        Source.lat as srcLat, Source.lng as srcLng,
	        Document.sentimentScore as sentimentScore";

	$orderBy = '';
	if (array_key_exists('sort', $_GET)) {
	    if ($_GET['sort'] === 'similarity') {
	        $orderBy = ' ORDER BY similarity ';
	    }
	    elseif ($_GET['sort'] === 'date') {
	        $orderBy = ' ORDER BY date ';
	    }
	    elseif ($_GET['sort'] === 'sentiment') {
	        $orderBy = ' ORDER BY sentimentScore ';
	    }
	    	}
	
	$groupBy = "GROUP BY Document.Id";

    $sql = $select . buildSearchQuery($orderBy, $groupBy);

	return $sql;
}


function buildGeoSearchQuery() {
	$select = "SELECT
		Document.id as docId,
        Destination.id as dstId,
        Destination.name as dstName,
        Destination.lat as dstLat,
        Destination.lng as dstLng,
        Source.id as srcId,
        Source.name as srcName,
        Source.lat as srcLat,
        Source.lng as srcLng";
	$groupBy = "GROUP BY Document.id";
	$orderBy = "";	
	$innerSql = $select . buildSearchQuery($orderBy, $groupBy);

	$sql = "
		select placeId id,
		        placeName name,
		        sum(dstFreq) incoming,
		        sum(srcFreq) outgoing,
		        sum(allFreq) traffic,
		        lat,
		        lng
		from (
		select dstId placeId,
		        dstName placeName,
		        count(*) dstFreq,
		        0 srcFreq,
		        count(*) allFreq,
		        dstLat lat,
		        dstLng lng
		from ( $innerSql) innerQuery
		group by placeId, placeName
		union
			select srcId placeId,
		        srcName placeName,
		        0 dstFreq,
		        count(*) srcFreq,
		        count(*) allFreq,
		        srcLat lat,
		        srcLng lng
		from ( $innerSql ) innerQuery
		group by placeId, placeName
		) allPoints
		group by placeId, placeName, lat, lng";
	return $sql;			
}



function buildPlaceCloudSearchQuery() {//Place Cloud

	$select = "SELECT Document.id as document_id";
	$groupBy = "GROUP BY Document.id";
	$orderBy = "";	
	$innerSql = $select . buildSearchQuery($orderBy, $groupBy);
	
	
	$sql = "
		SELECT trim(pr.text) as text,
			count(*) as weight
		FROM
			PlaceReference pr
		INNER JOIN
			( {$innerSql} ) docs
			ON pr.docId = docs.document_id 
		GROUP BY  trim(pr.text)
		ORDER BY weight DESC
		LIMIT 50;
	";
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
		GROUP BY  trim(pr.text)
		ORDER BY weight DESC
		LIMIT 50;
	";

	return $sql;	
}



function buildWordCloudSearchQuery() {//Text Cloud

	$select = "SELECT Document.id as document_id";
	$groupBy = "GROUP BY Document.id";
	$orderBy = "";	
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

	return $sql;	
}


function buildNetworkSearchQuery() {//Correspondence network
	logString('buildNetworkSearchQuery');

	$select = "SELECT Document.id as document_id, Document.sentFromPerson as sentFromPerson, Document.sentToPerson as sentToPerson";
	$groupBy = "GROUP BY Document.id";
	$orderBy = "";	
	logString('calling buildSearchQuery');
	$innerSql = $select . buildSearchQuery($orderBy, $groupBy);
	logString('done calling buildSearchQuery');
	logString($innerSql);
		
	
	$sql = "
		SELECT
			sender.id as sender_id,
			sender.name as sender_name,
			recipient.id as recipient_id, 
			recipient.name as recipient_name,
			count(*) as weight
		FROM
			( {$innerSql} ) docs
		INNER JOIN NormalizedPerson sender
			ON docs.sentFromPerson = sender.id
		INNER JOIN NormalizedPerson recipient
			on docs.sentFromPerson = recipient.id
		GROUP BY sender_id, sender_name, recipient_id, recipient_name
		ORDER BY weight DESC;
	";
	logString($sql);

	return $sql;	
}



?>