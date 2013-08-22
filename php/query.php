<?php
	class Query{
	
		public function getAuthors($connection){
			return mysqli_query( $connection, "SELECT author FROM Authors ORDER BY author");
		}

		public function getRecipients($connection){
			return mysqli_query( $connection, "SELECT recipient FROM Recipients ORDER BY recipient");
		}

		public function getOrigins($connection){
			return mysqli_query( $connection, "SELECT DISTINCT sent_from FROM Place ORDER BY sent_from");
		}
	
		public function getDestinations($connection){
			return mysqli_query( $connection, "SELECT DISTINCT sent_to FROM Place ORDER BY sent_to");
		}
	}
?>