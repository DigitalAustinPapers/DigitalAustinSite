<?php include('php/database.php'); ?>

<html>
	<header>
		<link rel="stylesheet" type="text/css" href="header.css" />
		<link rel="stylesheet" type="text/css" href="footer.css" />
		<link rel="stylesheet" type="text/css" href="style.css" /> <!-- Merged CSS -kmd -->
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
	</header>

		
	
	<body>
		<div id = "wrapper" class="shadow">
			<div id = "header">
				<?php include('header.php'); ?>
			</div>
			<div id="form">
				<h2>Search</h2>
				
				<!-- Begin form mockup -kmd -->
				
				<!-- Alternately, we could use tabs for the different kinds of search as in the search results. 
				I didn't mock this up because I was unsure if it would exist in the final version. -kmd  -->
				<p>Use the text search below or try the <a href="#">Geographic Search</a></p>
				
				<form action="results.php" method="post">
<div id="searchBox">
	search: <input type="text" name="textSearch" size="30"/>
	<input type="submit" name="title" value="go"/>
	<br><br>
  			author: <select name="author" style="width:145px;">
		<option value="">any…</option>
		<option value="a c ainsworth">a c ainsworth</option>
<option value="a c taylor">a c taylor</option>
<option value="a carnahan">a carnahan</option>
<option value="a dilliar">a dilliar</option>
</select>
	<!-- <br><br> -->
	recipient: <select name="recipient" style="width:150px;">
	<option value="">any…</option>
		<option value="adner kuykendall">adner kuykendall</option>
<option value="ahumada">ahumada</option>
<option value="alcalde of san felipe">alcalde of san felipe</option>
<option value="alexander somervell">alexander somervell</option>
<option value="alexenander calvit">alexenander calvit</option>
</select>
	<br><br>
	from year: <select name="fromYear" style="width:100px;">
		<option value="">any…</option>
		<option value="1822">1822</option>
<option value="1823">1823</option>
<option value="1824">1824</option>
<option value="1825">1825</option>

	</select>
	<!-- <br><br> -->
	to year: <select name="toYear" style="width:85px;">
		<option value="">any…</option>
		<option value="1822">1822</option>
<option value="1823">1823</option>
<option value="1824">1824</option>
<option value="1825">1825</option>

	</select>
	<br><br>
	sent from: <select name="from" style="width:100px;">
		<option value="">any…</option>
			</select>
	<!-- <br><br> -->
	sent to: <select name="to" style="width:90px;">
		<option value="">any…</option>
			</select>
	</div>
</form>	
				
				<!-- End form mockup -kmd -->
				
				
				
			</div>
			<div id = "footer">
				<?php include('footer.php'); ?>
			</div>
		</div>
	</body>
</html>