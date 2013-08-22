<?php
	
	$authorQuery = "SELECT author FROM authors ORDER BY author";
	$recipientQuery = "SELECT recipient FROM recipients ORDER BY recipient";
	$fromQuery = "SELECT DISTINCT sent_from FROM place ORDER BY sent_from";
	$toQuery = "SELECT DISTINCT sent_to FROM place ORDER BY sent_to";
		
	$findAuthor=mysqli_query( $connection, $authorQuery);
	$findRecipient=mysqli_query( $connection, $recipientQuery);
	$findFrom=mysqli_query( $connection, $fromQuery);
	$findTo=mysqli_query( $connection, $toQuery);
	
	$numAuthors=mysqli_num_rows($findAuthor);
	$numRecipients=mysqli_num_rows($findRecipient);
	$numFrom=mysqli_num_rows($findFrom);
	$numTo=mysqli_num_rows($findTo);

?>

<form action="geographic.php" method="post">
<div id="searchBox">
	search: <input type="text" name="textSearch" size="30"/>
	<input type="submit" name="title" value="go"/>
	<br><br>
  			author: <select name="author" style="width:145px;">
		<option value="">any...</option>
		<?php
			$i=0;
			while ($i < $numAuthors) {
				$row = mysqli_result($findAuthor,$i);
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
				$row = mysqli_result($findRecipient,$i);
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
			$i=1822;
			while ($i < 1849) {
				echo "<option value=\"$i\">$i</option>\n";
				$i++;
			}
		?>
	</select>
	<!-- <br><br> -->
	to year: <select name="toYear" style="width:85px;">
		<option value="">any...</option>
		<?php
			$i=1822;
			while ($i < 1849) {
				echo "<option value=\"$i\">$i</option>\n";
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
				$row = mysqli_result($findFrom,$i);
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
				$row = mysqli_result($findTo,$i);
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
