<?php

session_start();

//Set up our _SERVER variable as if we are acutally running it on the server
$_SERVER['DOCUMENT_ROOT'] = '/home/benwbrum/dev/clients/torget/dap/DigitalAustinSite';

require_once('../php/database.php');

$database = connectToDB();

$files = array_slice($argv, 1);
$command_line = true;


foreach ($files as $file)
{
    $doc = new DOMDocument();
    $success = $doc->load( $file );

#    print "-- Parsing $file\n";
    if (!$success)
    {
        print "-- Error parsing $file.  Skipped.";
        continue;
    }
	$geonames = $doc->getElementsByTagName("geoname");
#	print "-- found {$geonames->length} in {$file}\n";
	
	$foundOne = FALSE;
	$topAcceptibleMatch = null;
	
	foreach ($geonames as $geoname) {
		$fcode = $geoname->getElementsByTagName("fcode")->item(0)->textContent;
		$name = $geoname->getElementsByTagName("name")->item(0)->textContent;
		$continentCode = $geoname->getElementsByTagName("continentCode")->item(0)->textContent;
		$countryCode = $geoname->getElementsByTagName("countryCode")->item(0)->textContent;
		$adminName1 = $geoname->getElementsByTagName("adminName1")->item(0)->textContent;
		$adminCode1 = $geoname->getElementsByTagName("adminCode1")->item(0)->textContent;
		$lat = $geoname->getElementsByTagName("lat")->item(0)->textContent;
		$lng = $geoname->getElementsByTagName("lng")->item(0)->textContent;
		$thisRow = "I{$name}, {$adminCode1}\t{$countryCode}\t{$continentCode}\tfcode={$fcode}";		

		if("MX" == $countryCode) {
			$fullName = "{$name}, {$adminName1}";
		} elseif ("ADM1" == $fcode) {
			$fullName = $name;
		} else {
			$fullName = "{$name}, {$adminCode1}";
		}
		
		$thisRow = "INSERT INTO NormalizedPlace (name, lat, lng) VALUES (\"{$fullName}\", $lat, $lng); -- {$countryCode}\t{$continentCode}\tfc={$fcode}";

		
		# jettison things that are obviously bogus
		if("NA" != $continentCode) {
#			print "--skipping {$thisRow}:\t bad continent {$continentCode}\n";
			continue;
		}

		# jettison things that are obviously bogus
		if(!("US"== $countryCode || "MX"== $countryCode)) {
#			print "--skipping {$thisRow}:\t bad country {$countryCode}\n";
			continue;
		}
#		print "--considering {$thisRow}\n";
		$populatedPlace = ("PPL" == $fcode || "ADM1" == $fcode || "PPLA" == $fcode || "PPLA2" == $fcode);
		
		if("TX" == $adminCode1 && $populatedPlace) {
#			print "--keeping {$thisRow}, which is a populated place in Texas\n";
			$topAcceptibleMatch = $thisRow;
			break;
		}

		if("MO" == $adminCode1 && $populatedPlace) {
#			print "--keeping {$thisRow}, which is a populated place in Missouri\n";
			$topAcceptibleMatch = $thisRow;
			break;
		}

		if("KY" == $adminCode1 && $populatedPlace) {
#			print "--keeping {$thisRow}, which is a populated place in Kentucky\n";
			$topAcceptibleMatch = $thisRow;
			break;
		}

		if("TN" == $adminCode1 && $populatedPlace) {
#			print "--keeping {$thisRow}, which is a populated place in Tennessee\n";
			$topAcceptibleMatch = $thisRow;
			break;
		}

		if("VA" == $adminCode1 && $populatedPlace) {
#			print "--keeping {$thisRow}, which is a populated place in Virginia\n";
			$topAcceptibleMatch = $thisRow;
			break;
		}

		if("LA" == $adminCode1 && $populatedPlace) {
#			print "--keeping {$thisRow}, which is a populated place in Louisiana\n";
			$topAcceptibleMatch = $thisRow;
			break;
		}

		# if we get here, we haven't found a perfect record, but haven't rejected one 
		# either
		$foundOne = TRUE;
		
		if(null == $topAcceptibleMatch) {
			$topAcceptibleMatch = $thisRow;
		}
		
	}
	if($topAcceptibleMatch != null) {
		print "{$topAcceptibleMatch}\n";
	} else {
		print "-- MANUAL RESEARCH: nothing acceptible for {$file}\n";
	}
	
	
}

?>

