<?php include('php/database.php'); ?>

<html>
	<header>
		<link rel="stylesheet" type="text/css" href="header.css" />
		<link rel="stylesheet" type="text/css" href="footer.css" />
		<link rel="stylesheet" type="text/css" href="style.css" /> <!-- Merged CSS -kmd -->
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
	</header>

	<?php $connection = connectToDB(); ?>	
	
	<body>
		<div id = "wrapper" class="shadow">
			<div id = "header">
				<?php include('header.php'); ?>
			</div>
			<div id="form">
				<h2>Advanced Search</h2>
				<?php include('php/advanced.php'); ?>
			</div>
			<div id = "footer">
				<?php include('footer.php'); ?>
			</div>
		</div>
	</body>
</html>