<?php include('php/database.php'); ?>
<?php include('php/cloud.php'); ?>

<html>
	<head>
		<title>Stephen F. Austin - Digital Collection</title>
		
		<link rel="stylesheet" type="text/css" href="header.css" />
		<link rel="stylesheet" type="text/css" href="footer.css" />
		<link REL=StyleSheet HREF="results.css" TYPE="text/css">
		
		<!-- Simile Timeline scripts -->
		<?php include('php/timelineScripts.php'); ?>
		
		<!-- Word cloud script -->
		<?php include('php/cloudScripts.php'); ?>
		
		<script language="javascript">
			function toggleDiv(divid,imgname){
				if(document.getElementById(divid).style.display == 'none'){
					document.getElementById(divid).style.display = 'block';
					document[imgname].src = "pics/collapseIcon.gif";
				}
				else{
					document.getElementById(divid).style.display = 'none';
					document[imgname].src = "pics/expandIcon.gif";
				}
			}
		</script>
	</head>
	
	<?php $connection = connectToDB(); ?>
	
	<!-- Load the timeline -->
	<?php include('php/timeline.php'); ?>
	
	<!-- onLoad script -->
	<body onload="onLoad();" onresize="onResize();" >
	
		<!-- wrapper div -->
		<div id="wrapper" class="shadow">
			
			<div id = "header">
				<?php include('header.php'); ?>
			</div>
		
			<div class="expand_collapse">
				<a href="javascript:;" onmousedown="toggleDiv('results_layer2','cloudsToggle');">
					<img name="cloudsToggle" src='pics/collapseIcon.gif'/>
					<b>Word Clouds</b>
				</a>
			</div>
			
			<div id="results_layer2">
				<div id="results_wordCloud">
					<h3>Text</h3>
					<div id="cloud">
						
						<?php
							include("cloud.php");
							$body_tokens = explode(" ", $body);
							//make_cloud($body_tokens, array_count_values($body_tokens));
							
							/*
							if(strlen($body) > 0){
								cloud($body, 20);
							}
							else{
								echo("<p style='text-align: center;'> Insufficient data </p>");
							}
							*/
						?>						
					</div>
				</div>
				<div id="results_peopleCloud">
					<h3>People</h3>
					<div id="cloud"><?php
							if(strlen($people) > 0){
								cloud($people, 20);
							}
							else{
								echo("<p style='text-align: center;'> Insufficient data </p>");
							}	
						?>			
					</div>
				</div>
				<div id="results_placesCloud">
					<h3>Places</h3>
					<div id="cloud">
						<?php
							if(strlen($places) > 0){
								cloud($places, 20);
							}
							else{
								echo("<p style='text-align: center;'> Insufficient data </p>");
							}	
						?>		
					</div>
				</div>
			</div>
			
			<div class="expand_collapse">
				<a href="javascript:;" onmousedown="toggleDiv('results_layer1','timelineToggle');">
					<img name="timelineToggle" src='pics/collapseIcon.gif'/>
					<b>Interactive Timeline</b>
				</a>
			</div>
			
			<div id="results_layer1">
				<!-- <h3>Interactive Timeline</h3> -->
			
				<div id="my-timeline"></div>
			
				<!-- Timeline notes div -->
				<div id="timelineNotes">
					You can click and drag both the top (month) and bottom (year) of the timeline
				</div>
			</div>
			
			<div id="results_layer3">
				
				<!-- Display results (generated in timeline.php) -->
				<div id="results">
					<?php echo $results; ?>
				</div>
			</div>
			<div id = "footer">
				<?php include('footer.php'); ?>
			</div>
		</div>	
	</body>
</html>
