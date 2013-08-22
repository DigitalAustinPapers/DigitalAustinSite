<?php

session_start();

include 'porterStemmer.php';
include '../php/database.php';

function vectorMagnitude($vector)
{
    $magnitude = 0;
    foreach ($vector as $stem => $count)
    {
        $magnitude += ($count * $count);
    }
    return sqrt($magnitude);
}

?>

<h1>Digital Austin Papers Backend</h1>

<form action="search.php" method="GET">
<input type="text" name="query"/>
<input type="submit" value="Search">
</form>

<?

$database = connectToDB();

$stemIndex = array();
if (array_key_exists('query', $_GET))
{
    //Stem and split the query
    $stemmer = new PorterStemmer();
    $text = trim(strtolower($_GET['query']));
    //Split on non-word characters
    $words = preg_split('/[\W]+/', $text);
    //Stem each word
    $stems = array_map(array($stemmer, 'Stem'), $words);
    $stemCounts = array_count_values($stems);

    print "<h2>Search results</h2>";
    print "<table>";
    $stemWhereClause = "WHERE stem in (NULL";
    foreach ($stemCounts as $stem => $count)
    {
        $escapedStem = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $stem) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
        $stemWhereClause .= ", '$escapedStem'";
    }
    $stemWhereClause .= ')';

    //An sql statement to extract the TF values of the terms from the query
    $sqlStemCount = "
    SELECT docId, stem, count
    FROM StemCount
    INNER JOIN Document
    ON docId=Document.id
    $stemWhereClause";

    //An sql statement to calculate the IDF values of the terms from the query.
    $sqlIdf = "
    SELECT stem, log(docCount/(1 + count(docId))) as idf
    FROM StemCount
    INNER JOIN (select count(id) as docCount FROM Document) as DocCount
    $stemWhereClause
    GROUP BY stem";

    //An sql statement that determines the magnitude of each document's
    //tf-idf vector.  Ignores documents with no matching stems.
    $sqlDocMagnitude = "
    SELECT docId, sqrt(sum(POW(count*idf, 2))) as docMagnitude FROM StemCount
    INNER JOIN (
        SELECT stem, log(docCount/(1 + count(docId))) as idf FROM StemCount
        INNER JOIN (
            SELECT count(id) as docCount FROM Document
        ) as DocCount
        GROUP BY stem
    ) as Idf
    ON Idf.stem=StemCount.stem
    WHERE docId in (
        SELECT docId FROM StemCount
        $stemWhereClause
        GROUP BY docId
    )
    GROUP BY docId
    ";
    print "$sqlStemCount<br>";
    print "$sqlIdf<br>";

    $idf = array();
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $sqlIdf) or die($sqlIdf . "<br>" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
    while ($row = mysqli_fetch_array($result))
    {
        $idf[$row['stem']] = $row['idf'];
    }

    //Create an index for the magnitudes of the document vectors.
    $docMagnitude = array();
    $result = mysqli_query($GLOBALS["___mysqli_ston"], $sqlDocMagnitude) or die($sqlDocMagnitude . "<br>" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
    while ($row = mysqli_fetch_array($result))
    {
        $docMagnitude[$row['docId']] = $row['docMagnitude'];
    }

    $result = mysqli_query($GLOBALS["___mysqli_ston"], $sqlStemCount) or die($sqlStemCount . "<br>" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
    while ($row = mysqli_fetch_array($result))
    {
        if (!array_key_exists($row['docId'], $stemIndex))
        {
            $stemIndex[$row['docId']] = array();
        }
        $stemIndex[$row['docId']][$row['stem']] = $row['count'];
    }

    $queryMagnitude = vectorMagnitude($stemCounts);
    $similarity = array();
    foreach($stemIndex as $docId => $docStemCounts)
    {
        $similarity[$docId] = 0;
        foreach ($docStemCounts as $stem => $count)
        {
            $similarity[$docId] += ($count * $idf[$stem] * $stemCounts[$stem] * $idf[$stem]);
        }
        $similarity[$docId] /= $queryMagnitude;
        $similarity[$docId] /= $docMagnitude[$docId];
    }
    arsort($similarity);
    foreach ($similarity as $doc => $simScore)
    {
        print "$doc : $simScore<br>";
    }
    print "</table>";
}

?>

