<html>
	<header>
		<link rel="stylesheet" type="text/css" href="header.css" />
		<link rel="stylesheet" type="text/css" href="footer.css" />
		<link rel="stylesheet" type="text/css" href="index.css" />
	</header>

	<body>
		<div id = "wrapper" class="shadow">
			<div id = "header">
				<?php include('header.php'); ?>
			</div>
			<div id = "layer1">
				<div id = "austin_image"><img src="pics/AustinBackground.png" width=280px height=264px/></div>
				<div id = "blurb">
					<div id = "blurb_text">
						<p>The Digital Austin Papers Project seeks to recreate the turbulent world of the Texas borderlands during the 1820s and 1830s, as seen through the eyes of Stephen F. Austin.   Collecting thousands of letters by both Mexicans and Americans, the project will offer unprecedented access to the movement of ideas and people during the early nineteenth century that transformed the borderlands between the United States and Mexico.</p>   
						<p>The project will digitize the complete writings and correspondence of Austin, making them accessible and searchable online for the first time.  Yet the project will go far beyond simply making these records available digitally�we will be offering new digital tools for exploring the language, geography, and ideas embedded in the Austin correspondence.</p>
						<p>For a complete report on the materials available, check the updates and status section.</p>
					</div>
					<div id = "blurb_search">
						<form action="results.php" method="get">
							Explore the Austin Papers: <input type="text" name="query" size="30">
							<input type="submit" name="title" value="Submit">
						</form>
					</div>
				</div>
			</div>
			<div id = "layer2">
				<div id = "box1">
					<div id = "about"><h3>About the Project</h3>
						<ul>
							<li><a href="idea.php">Idea Behind the Project</a></li>
							<li><a href="updates.php">Updates and Status</a></li>
							<li><a href="partners.php">Project Partners</a></li>
							<li><a href="staff.php">Project Staff</a></li>
						</ul>
					</div>
					<div id = "updates"><h3>Updates/RSS</h3></div>
				</div>
				<div id = "box2"><h3>Featured Searches</h3>
					<ul>
						<li><a href="results.php?textSearch=cholera">Cholera epidemic in the 1830s</a></li>
						<li><a href="results.php?recipient=Emily M Perry">Stephen Austin�s Letters to his sister, Emily</a></li>
						<li><a href="results.php?textSearch=comanches">Problems with Comanches</a></li>
						<li><a href="results.php?to=New Orleans, Louisiana">Letters from Texas to New Orleans, LA</a></li>
					</ul>
				</div>
				<div id = "box3"><h3>Partners</h3>
					<ul>
						<li>Digital History Lab, UNT</li>
						<li><a href="http://texashistory.unt.edu">Portal to Texas History, UNT</a></li>
						<li><a href="http://www.cah.utexas.edu/">Center for American History, University of Texas</a></li>
						<li><a href="http://www.glo.texas.gov/">Texas General Land Office</a></li>
						<li><a href="http://www.summerlee.org/">Summerlee Foundation</a></li>
						<li><a href="http://www.tshaonline.org/">Texas State Historical Association</a></li>
					</ul>
				
				</div>
			</div>
			<div id = "footer">
				<?php include('footer.php'); ?>
			</div>
		</div>
	</body>
</html>
