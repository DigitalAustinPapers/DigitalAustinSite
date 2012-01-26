<html>
	<header>
		<link rel="stylesheet" type="text/css" href="header.css" />
		<link rel="stylesheet" type="text/css" href="footer.css" />
		<link rel="stylesheet" type="text/css" href="contact.css" />
	</header>

	<body>
		<div id = "wrapper" class="shadow">
			<div id = "header">
				<?php include('header.php'); ?>
			</div>
			<div id = "form">
				<h2>Contact Form</h2> 
			<p>Please fill out the form below to send email to the Digital Austin Collection team <em>(all fields are required)</em>:</p> 
			<form action="send.php" method="post"> 
				<p>Your Name:<br /> 
				<input type="text" name="contact_name" size="30" maxlength="100" tabindex="1" /> 
				</p> 
				<p>Your Email Address:<br /> 
				<input type="text" name="contact_email" size="30" maxlength="100" tabindex="2" /> 
				</p> 
				<p>Reason for Email:
				<select name="contact_reason" size="1" tabindex="3"> 
					<option label="Question" value="question" selected="selected">Question</option> 
					<option label="Comment" value="comment">Comment</option> 
					<option label="Suggestion" value="suggestion">Suggestion</option> 
					<option label="Problem" value="problem">Problem</option> 
				</select> 
				</p> 
				<p>Your Message:<br /> 
				<textarea name="contact_message" rows="8" cols="40" tabindex="4"></textarea> 
				</p>
				<p> 
				<input type="submit" name="Submit" value="Send!" tabindex="5" /> 
				</p> 
			</form> 
			</div>
			<div id = "footer">
				<?php include('footer.php'); ?>
			</div>
		</div>
	</body>
</html>