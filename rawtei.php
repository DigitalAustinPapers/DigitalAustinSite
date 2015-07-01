<?php
require_once 'src/TemplateRenderer.class.php';
include_once('php/database.php');

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
$xml = str_replace('<?xml version="1.0" encoding="Windows-1252"?>', '', $result['xml']);

// Set the header to the correct content-type
header('Content-type: text/xml');

$template = new TemplateRenderer();
// Include any variables as an array in the second param
print $template->render('rawtei.xml.twig', array(
                        'xml' => $xml,
));