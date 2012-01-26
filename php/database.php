<?php

	function connectToDB() {
		/*
		$username="SecureGuest";
		$password="Password1";
		$database="austincollection";
			
		$connection = mysql_connect("austincollection.db.7972777.hostedresource.com",$username,$password);
		@mysql_select_db($database, $connection) or die( "Unable to select database");
		return $connection;
		*/
		
		$username="root";
		$password="";
		$database="austincollection";
		//$database = "austinpapers";
	
		$connection = mysql_connect("127.0.0.1:3306/",$username,$password);
		@mysql_select_db($database, $connection) or die( "Unable to select database");
		return $connection;
	}
	
?>
