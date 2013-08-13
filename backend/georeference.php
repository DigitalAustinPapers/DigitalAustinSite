<?php
# this creates a wget script that queries geonames.org for all PlaceReference strings.
# When the output is piped to bash, it will populate a directory with XML files containing
# results from the queries

session_start();

//Set up our _SERVER variable as if we are acutally running it on the server
$_SERVER['DOCUMENT_ROOT'] = '/home/benwbrum/dev/clients/torget/dap/DigitalAustinSite';

require_once('../php/database.php');

$database = connectToDB();


$result = mysql_query("SELECT text, count(*) total FROM PlaceReference GROUP BY text");
while ($row = mysql_fetch_array($result))
{
	$raw_text = $row['text'];
	$raw_text = str_replace("(", '', $raw_text);
	$raw_text = str_replace(")", '', $raw_text);
	$raw_text = str_replace("\"", '', $raw_text);
	$raw_text = str_replace("'", '', $raw_text);
	$raw_text = str_replace("[", '', $raw_text);
	$raw_text = str_replace("]", '', $raw_text);
    $text = urlencode($raw_text);
	$fuzzy = 0.8;
#	print "[{$raw_text}]\n";
	print "wget \"http://api.geonames.org/search?q={$text}&maxRows=10&fuzzy={$fuzzy}&username=digitalaustinpapers&countryCode=US&countryCode=MX&style=FULL\" -O\"geonames/{$raw_text}.xml\"\n";
}


?>