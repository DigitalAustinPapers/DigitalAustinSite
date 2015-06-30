<?php

header('Content-type: text/xml');
  print '<?xml version="1.0" encoding="Windows-1252"?>';
  print "\n";
  print '<?xml-model href="http://www.tei-c.org/release/xml/tei/custom/schema/relaxng/tei_all.rng" schematypens="http://relaxng.org/ns/structure/1.0"?>';
  print "\n";
  print '<?xml-stylesheet type="text/xsl" href="xsl/teibp.xsl"?>';
  print "\n";


include('php/database.php');

function queryDB(){
  $id = $_GET['id'];
  $new_id = str_replace(".xml", "", $id);

  $query =
    "SELECT d.id id,
        d.xml xml
     FROM Document d
     WHERE d.id = '$new_id'";

  $result = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));;

  return mysqli_fetch_assoc($result);
}

connectToDB();
$result = queryDB();
print str_replace('<?xml version="1.0" encoding="Windows-1252"?>', '', $result['xml']);