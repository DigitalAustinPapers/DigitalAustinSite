<?php
  include('php/database.php');
  include('php/document.php');
  include('header.php');
?>

			<?php
				connectToDB();
				$result = queryDB();
				getTitleStatusSummary($result);
			?>
			<!-- Moved TEI button here -KMD -->
			<div id="teixml">
				<a href="rawtei.php?id=<?php print $result['id'] ?>">
					<img src="assets/img/xml-tei_button.gif" />
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
			
<?php include('footer.php'); ?>
