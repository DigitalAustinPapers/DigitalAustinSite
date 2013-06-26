<?php include('php/database.php'); ?>
<?php include('php/document.php'); ?>

<html>
	<header>
		<link rel="stylesheet" type="text/css" href="header.css" />
		<link rel="stylesheet" type="text/css" href="footer.css" />
		<link rel="stylesheet" type="text/css" href="document.css" />
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
			<div id="cite">Citation: John W. Smith, Collection of Austin's Letters, First Edition, 1937</div>
			<div id = "footer">
				<?php include('footer.php'); ?>
			</div>
		</div>
	</body>
</html>