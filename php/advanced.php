<?php

	$authorQuery = "SELECT author FROM authors ORDER BY author";
	$recipientQuery = "SELECT recipient FROM recipients ORDER BY recipient";
	$fromQuery = "SELECT DISTINCT sent_from FROM place ORDER BY sent_from";
	$toQuery = "SELECT DISTINCT sent_to FROM place ORDER BY sent_to";
	$yearQuery = "SELECT DISTINCT left(creation,4) FROM Document WHERE creation > '1' ORDER BY 1";
		
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
  			author: <select name="author" style="width:145px;">
		<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numAuthors) {
				$row = mysql_result($findAuthor,$i);
				if (strlen($row) > 80){
					$row = substr($row, 0, 80);
				}
				echo "<option value=\"$row\">$row</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	<!-- <br><br> -->
	recipient: <select name="recipient" style="width:150px;">
	<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numRecipients) {
				$row = mysql_result($findRecipient,$i);
				if (strlen($row) > 80){
					$row = substr($row, 0, 80);
				}
				echo "<option value=\"$row\">$row</option>\n";
				$option = $row;
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
				$row = mysql_result($findYears,$i);
				echo "<option value=\"$row\">$row</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	<!-- <br><br> -->
	to year: <select name="toYear" style="width:85px;">
	<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numYears) {
				$row = mysql_result($findYears,$i);
				echo "<option value=\"$row\">$row</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	<br><br>
	sent from: <select name="from" style="width:100px;">
		<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numFrom) {
				$row = mysql_result($findFrom,$i);
				if (strlen($row) > 125){
					$row = substr($row, 0, 125);
				}
				echo "<option value=\"$row\">$row</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	<!-- <br><br> -->
	sent to: <select name="to" style="width:90px;">
		<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numTo) {
				$row = mysql_result($findTo,$i);
				if (strlen($row) > 40){
					$row = substr($row, 0, 40);
				}
				echo "<option value=\"$row\">$row</option>\n";
				$option = $row;
				$i++;
			}
		?>
	</select>
	</div>
</form>