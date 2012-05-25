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

        //If a file exists which contains the credentials to the local
        //server, use those.  Otherwise, use the default credentials
        //This file should not be uploaded to the svn.
        //
        //Example contents of localCredentials.php:
        //    function getCredentials() {
        //        $result = array(
        //            'username' => "root",
        //            'password' => "my-secret-password",
        //            'database' => "austinpapers",
        //            'server' => "127.0.0.1:3306/");
        //        return $result;
        //    }
        //
        $credentials = array();
        $localFile = $_SERVER["DOCUMENT_ROOT"] . '/php/localCredentials.php';
        if (file_exists($localFile)) {
            require_once($localFile);
            $credentials = getCredentials();
        }
        else
        {
            //Use the default credentials
            $credentials = array('username' => "root",
                'password' => "",
                'database' => "austincollection",
                //'database' => "austinpapers",
                'server' => "127.0.0.1:3306/");
        }

		$connection = mysql_connect($credentials['server'],
            $credentials['username'], $credentials['password']);
		@mysql_select_db($credentials['database'], $connection)
            or die( "Unable to select database");
		return $connection;
	}
	
?>
