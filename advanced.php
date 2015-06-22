<?php
  include('php/database.php');
  include('header.php');
  $connection = connectToDB();
?>

			<div id="form">
				<h2>Advanced Search</h2>
				<?php include('php/advanced.php'); ?>
			</div><!-- form -->

<?php include('footer.php'); ?>
