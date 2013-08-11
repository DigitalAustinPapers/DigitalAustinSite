<?php include('php/database.php'); ?>
<?php include('php/query.php') ?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="header.css" />
		<link rel="stylesheet" type="text/css" href="footer.css" />
		<link rel="stylesheet" type="text/css" href="style.css" /> <!-- Merged CSS -kmd -->
		<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
		
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false">
		</script>
		
		<?php
			global $connection;
			$connection = connectToDB();			
			global $queries;
			$queries = new Query();
		?>

		<script type="text/javascript">
			function initialize(){
				alert('yo momma');
				<?php include('php/geographicjavascript.php'); ?>
			}
		</script>

	</head>

	<body onload="initialize()">
		<div id = "wrapper" class="shadow">
			<div id = "header">
				<?php include('header.php'); ?>
			</div>
			<div id="form">
				<h2>Geographic Search</h2>
				<?php include('php/advancedgeographic.php'); ?>
			</div>
			
			<p> Showing results for: <?php echo $textSearch; ?> </p>
			
			<div id="map_canvas" style="width:1000px; height:500px"></div>
			
			<!--
			<form name='myform' action='test.php' method='POST'>
				<input type="hidden" name='places' value='<?php //print_r(str_replace("'", "*", serialize($place))); ?>;' />		
				<input type='submit' name='map' value='map'>
			</form>
			-->
			
			<div id = "footer">
				<?php include('footer.php'); ?>
			</div>
		</div>
	</body>
</html>
