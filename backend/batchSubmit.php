<?php

session_start();

//Set up our _SERVER variable as if we are acutally running it on the server
$_SERVER['DOCUMENT_ROOT'] = '/home/benwbrum/dev/clients/torget/dap/DigitalAustinSite';

require_once('../php/database.php');

$database = connectToDB();

$files = array_slice($argv, 1);
$command_line = true;

#Returns an array of character n-grams from the target string
function nCharGrams($targetString, $n)
{
    $grams = array();
    $chars = str_split($targetString);
    for($i = 0; $i < count($chars) - $n + 1; $i++)
    {
        $gram = '';
        for ($j = 0; $j < $n; $j++)
        {
            $gram .= $chars[$i + $j];
        }
        array_push($grams, $gram);
    }
    return $grams;
}


#Returns the Jaccard Similarity Coefficient of two strings
#http://en.wikipedia.org/wiki/Jaccard_index
#a and b are short strings, approximately equal in length,
#to be compared. The returned value is between 0 and 1.
#Identical strings return 1.
function jaccardSim($a, $b)
{
    $gramsA = nCharGrams(strtolower($a), 2);
    $gramsB = nCharGrams(strtolower($b), 2);
    $intersect = count(array_unique(array_intersect($gramsA, $gramsB)));
    $union = count(array_unique(array_merge($gramsA, $gramsB)));
    return $intersect / $union;
}

function getTag($personName, $key, $knownNames)
{
    $normalizedName = "(Not Tagged)";
    $suggestion = "~";
    if ($key != NULL)
    {
        if (array_key_exists($key, $knownNames))
        {
            $normalizedName = $knownNames[$key][0];
        }
    }
    $suggestion = "";
    $bestScore = 0.3;
    $bestId = '';
    foreach($knownNames as $id => $forms)
    {
        $standardForm = $forms[0];
        foreach($forms as $form)
        {
            $score = jaccardSim($personName, $form);
#			print "{$personName} compared to {$form} with a score of {$score}\n";
            if ($score > $bestScore)
            {
                if ($form == $standardForm)
                {
                    $suggestion = $standardForm;
                }
                else
                {
                    $suggestion = "$standardForm (aka $form)";
                }
                $bestId = $id;
                $bestScore = $score;
				$bestName=$form;
            }
        }
    }
    if ($bestId !== '')
    {
		#logString("{$personName} matched to {$bestName} (id: {$bestId}) with a score of {$bestScore}");
        return $bestId;
    }
    return $key;
}

foreach ($files as $file)
{
    $doc = new DOMDocument();
    $success = $doc->load( $file );

    print "Parsing $file\n";
    if (!$success)
    {
        print "Error parsing $file.  Skipped.";
        continue;
    }

    $_SESSION['teiXml'] = $doc->saveXML();

    //Generate lists of normalized people and places for comparison to the input.
    $knownNames = array();
    $result = mysql_query("SELECT id, name FROM NormalizedPerson ORDER BY name");
    while ($row = mysql_fetch_array($result))
    {
        $knownNames[$row['id']] = array($row['name']);
    }
    $knownPlaces = array();
    $result = mysql_query("SELECT id, name FROM NormalizedPlace ORDER BY name");
    while ($row = mysql_fetch_array($result))
    {
        $knownPlaces[$row['id']] = array($row['name']);
    }
    // $result = mysql_query("SELECT normalId, text FROM PlaceReference where normalId IS NOT NULL");
    // while ($row = mysql_fetch_array($result))
    // {
        // array_push($knownPlaces[$row['normalId']], $row['text']);
    // }

    //Create the table of people with suggested normalized values
    $people = $doc->getElementsByTagName("persName");
    $index = 0;
    $_POST['personTag'] = array();
    foreach( $people as $person )
    {
        $name = $person->textContent;
        $keyNode = $person->attributes->getNamedItem("key");
        $key = null;
        if ($keyNode)
        {
            $key = $keyNode->nodeValue;
        }
	    #logString("Posting name={$name} key={$key} as personTag[{$index}] = {getTag($name, $key, $knownNames)}\n");    	
        $_POST['personTag'][$index] = getTag($name, $key, $knownNames);
		#logString("setting $_POST[personTag][$index]={$_POST['personTag'][$index]}");
		
        $index += 1;
    }

    //Create a table for tagging places
    $places = $doc->getElementsByTagName("placeName");
    $index = 0;
    $_POST['placeTag'] = array();
    foreach( $places as $place )
    {
        $name = $place->textContent;
        $keyNode = $place->attributes->getNamedItem("key");
        $key = '';
        if ($keyNode)
        {
            $key = $keyNode->nodeValue;
        }
        $_POST['placeTag'][$index] = getTag($name, $key, $knownPlaces);
        $index += 1;
    }
    require("submit.php");
}

?>

