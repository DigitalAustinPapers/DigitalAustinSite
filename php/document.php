<?php

	function loadScript(){
		echo("
			<script>
			    window.onload = function()
			    {
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
			</script>"
		);
	}
	
	function queryDB(){
		$id = $_GET['id'];
		$new_id = str_replace(".xml", "", $id);
		//$query = "SELECT * FROM Document NATURAL JOIN Place NATURAL JOIN Text WHERE idDocument = '$id'";
		$query = "SELECT * FROM Document WHERE id = '$new_id'";
		//$query =   "SELECT * FROM document NATURAL JOIN text                    WHERE idDocument = '$id'";

		$result = mysql_query($query) or die(mysql_error());;
		
		return mysql_fetch_assoc($result);
	}

	function getTitleStatusSummary($row){
		echo "<div id='title'>" . $row["title"] . "</div>";
		//echo "<div id='metadata'>Sent from: " . $row["sent_from"] . " to: " . $row["sent_to"] . ", Original Language: " . "English" . ", Status: " . $row["status"] . " " . $row["type"] . "</div>";
		echo "<div id='summary'>Summary: " . $row["summary"] . "</div>";
	}
	
	
	// TODO: handle converting HI to I via xslt (later)
	function getCitation($row) {
		$raw_xml = $row['xml'];
		
		$doc = new DOMDocument();
    	$success = $doc->loadXml( $raw_xml );
		
		# we're looking for contents like this:
		#        <div1 type="body">
 		$all_bibls = $doc->getElementsByTagName('bibl');
		logString("all_bibls->length = {$all_bibls->length}");
		$bibl = $all_bibls->item(0);
		$citeString = $doc->saveXML($bibl);
		logString("bibl = {$citeString}");
		
	
		// # now process the body
		// logString($body_node->textContent);
		
		return $citeString;
	}

	function getDocXmlFromRow($row) {
		$raw_xml = $row['xml'];
		$body_node = null;
		
		$doc = new DOMDocument();
    	$success = $doc->loadXml( $raw_xml );
		return $doc;		
	}
	
	function getLetterBodyNode($row, $doc=null) {
		if($doc == null) {
			$doc = getDocXmlFromRow($row);		
		}
		
		# we're looking for contents like this:
		#        <div1 type="body">
 		$all_div1s = $doc->getElementsByTagName('div1');
		foreach($all_div1s as $div1) {
			$typeNode = $div1->attributes->getNamedItem("type");
			if($typeNode) {
				$type = $typeNode->value;
				if($type == 'body') {
					$body_node = $div1;
				}
			}
			
		}
				
	
		return $body_node;
	}
		
	function getLetterBodyForCloud($row) {
		return getLetterBodyNode($row)->textContent;
	}
		
	function getLetterBodyForDisplay($row) {
		$doc = getDocXmlFromRow($row);
		$body_node = getLetterBodyNode($row, $doc);
		return $doc->saveXML($body_node);
	}
		
	
	function peopleMentioned($row){
		echo "<h3>People Mentioned:</h3><p>";
	
		//$query2 = "SELECT name FROM People NATURAL JOIN People_mentioned WHERE idDocument = '" . $row["idDocument"] . "' ORDER BY name";
		$query2 = "SELECT name FROM people WHERE idDocument = '" . $row["idDocument"] . "' ORDER BY name";
		$result2 = mysql_query($query2);
		$num2 = mysql_numrows($result2);
		$i2=0;
	
		while ($i2 < $num2) {
			$row2 = mysql_result($result2,$i2);
	
			echo "<p>$row2</p>";
	
			$i2++;
		}
		echo "</p>";	
	}
	
	function placesMentioned($row){
		echo "<h3>Places Mentioned:</h3><p>";
	
		//$query2 = "SELECT place FROM Places NATURAL JOIN Places_mentioned WHERE idDocument = '" . $row["idDocument"] . "' ORDER BY place";
		$query2 = "SELECT place FROM places WHERE idDocument = '" . $row["idDocument"] . "' ORDER BY place";
		$result2 = mysql_query($query2);
		$num2 = mysql_numrows($result2);
		$i2=0;
	
		while ($i2 < $num2) {
			$row2 = mysql_result($result2,$i2);
	
			echo "<p>$row2</p>";
	
			$i2++;
		}
		echo "</p>";	
	}

?>