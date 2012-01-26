<?php
	class Query{
	
		public function getAuthors($connection){
			return mysql_query("SELECT author FROM Authors ORDER BY author", $connection);
		}

		public function getRecipients($connection){
			return mysql_query("SELECT recipient FROM Recipients ORDER BY recipient", $connection);
		}

		public function getOrigins($connection){
			return mysql_query("SELECT DISTINCT sent_from FROM Place ORDER BY sent_from", $connection);
		}
	
		public function getDestinations($connection){
			return mysql_query("SELECT DISTINCT sent_to FROM Place ORDER BY sent_to", $connection);
		}
	}
?>