<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<?php include('php/database.php'); ?>
<?php include('php/document.php'); ?>

<html>

	<header>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

		<link rel="stylesheet" type="text/css" href="header.css" />
		<link rel="stylesheet" type="text/css" href="footer.css" />
		<link rel="stylesheet" type="text/css" href="style.css" /> <!-- Merged CSS -kmd -->
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
	</header>

	<body>
		<div id = "wrapper" class = "shadow">
			<?php loadScript(); ?>
			<div id = "header">
				<?php include('header.php'); ?>
			</div>
			<?php
				connectToDB();
				$result = queryDB();
				getTitleStatusSummary($result);
			?>
			<!-- Moved TEI button here -KMD -->
			<div id="teixml">
				<a href="rawtei.php?id=<?php print $result['id'] ?>">
					<img src="pics/xml-tei_button.gif" />
				</a>
			</div>
			<div id='text'>
				<?php print getLetterBodyForDisplay($result) ?>
			</div>	
			
			<div id="clouds_header">Language Analysis</div>
			<div id="clouds">
				<div id="cloud1"></div>
					<?php
						include('php/cloud.php');
#						cloud($result['xml'], 20);
						cloud(getLetterBodyForCloud($result), 20);
					?>
				<div id="cloud2"></div>
				<div id="cloud3"></div>
			</div>
			<div id="cite">Citation: <?php print getCitation($result) ?></div>
			
			<div id = "footer">
				<?php include('footer.php'); ?>
			</div>
		</div>
	</body>
</html>