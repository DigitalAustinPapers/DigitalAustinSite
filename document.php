<?php
require_once 'src/TemplateRenderer.class.php';
include_once 'php/database.php';
include_once 'php/cloud.php';

// Functions

function queryDB(){
  $id = $_GET['id'];
  $new_id = str_replace(".xml", "", $id);

  $query =
    "SELECT d.id id,
        d.xml xml,
        d.title title,
        d.summary summary,
        d.creation creation,
        d.vectorLength vectorLength,
        op.name origin,
        dp.name destination,
        ap.name author,
        rp.name recipient,
        rp.id  toPersonId,
        ap.id fromPersonId
     FROM Document d
     LEFT OUTER JOIN NormalizedPlace op
      ON d.sentFromPlace = op.id
     LEFT OUTER JOIN NormalizedPlace dp
      ON d.sentFromPlace = dp.id
     LEFT OUTER JOIN NormalizedPerson ap
      ON d.sentFromPerson = ap.id
     LEFT OUTER JOIN NormalizedPerson rp
      ON d.sentToPerson = rp.id
     WHERE d.id = '$new_id'";

  $result = mysqli_query($GLOBALS["___mysqli_ston"], $query) or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));;

  return mysqli_fetch_assoc($result);
}

// TODO: handle converting HI to I via xslt (later)
function getCitation($row) {
  $raw_xml = $row['xml'];

  $doc = new DOMDocument();
  $success = $doc->loadXml( $raw_xml );

  # we're looking for contents like this:
  #        <div1 type="body">
  $all_bibls = $doc->getElementsByTagName('bibl');
  $bibl = $all_bibls->item(0);
  $citeString = $doc->saveXML($bibl);

  // # now process the body
  // logString($body_node->textContent);

  return $citeString;
}

function getDocXmlFromRow($row) {
  $raw_xml = $row['xml'];
  $body_node = null;

  $doc = new DOMDocument();
  $success = $doc->loadXml( $raw_xml );
  return $doc;
}

function getLetterBodyNode($row, $doc=null) {
  if($doc == null) {
    $doc = getDocXmlFromRow($row);
  }

  # we're looking for contents like this:
  #        <div1 type="body">
  $all_div1s = $doc->getElementsByTagName('div1');
  foreach($all_div1s as $div1) {
    $typeNode = $div1->attributes->getNamedItem("type");
    if($typeNode) {
      $type = $typeNode->value;
      if($type == 'body') {
        $body_node = $div1;
      }
    }

  }

  return $body_node;
}

function getLetterBodyForCloud($row) {
  $raw_text = getLetterBodyNode($row)->textContent;
  $cloud_text = str_replace("\n", " ", $raw_text); #cloud chokes on newlines
  return $cloud_text;
}

function getLetterBodyForDisplay($row) {
  $doc = getDocXmlFromRow($row);
  $body_node = getLetterBodyNode($row, $doc);
  transformBodyForDisplay($doc, $body_node);
  return $doc->saveXML($body_node);
}


function transformBodyForDisplay($doc, $body) {
  # change people to links
  transformPersonNames($doc, $body);
  # change places to links
  transformPlaceNames($doc, $body);
}

function transformPersonNames($doc, $body) {
  $all_persNames = $body->getElementsByTagName('persName');

  while($all_persNames->length > 0) {
    logString("persName count={$all_persNames->length}");
    foreach($all_persNames as $persName) {
      $reference = $persName->textContent;
      # clean up the reference for text search
      $cleaned_reference = preg_replace('/^\s*/', '', $reference);
      $cleaned_reference = preg_replace('/\s*$/', '', $cleaned_reference);
      #			logString("reference [{$reference}] cleaned to [{$cleaned_reference}]");

      $search_target = urlencode($cleaned_reference);
      $link = $doc->createElement('a', $reference);
      $link->setAttribute('class', "document-person-name");
      $link->setAttribute('href', "search?query={$cleaned_reference}");
      $result = $persName->parentNode->replaceChild($link, $persName);

    }
    $all_persNames = $body->getElementsByTagName('persName');
    # continue until they are all transformed
  }

}

function transformPlaceNames($doc, $body) {
  $all_placenames = $body->getElementsByTagName('placeName');

  while($all_placenames->length > 0) {
    logString("placename count={$all_placenames->length}");
    foreach($all_placenames as $placename) {
      $reference = $placename->textContent;
      # clean up the reference for text search
      $cleaned_reference = preg_replace('/^\s*/', '', $reference);
      $cleaned_reference = preg_replace('/\s*$/', '', $cleaned_reference);
      #			logString("reference [{$reference}] cleaned to [{$cleaned_reference}]");

      $search_target = urlencode($cleaned_reference);
      $link = $doc->createElement('a', $reference);
      $link->setAttribute('class', "document-place-name");
      $link->setAttribute('href', "search?query={$cleaned_reference}");
      #			logString($link->textContent);
      $result = $placename->parentNode->replaceChild($link, $placename);

    }
    $all_placenames = $body->getElementsByTagName('placeName');
    # continue until they are all transformed
  }

}


function peopleMentioned($row){
  echo "<h3>People Mentioned:</h3><p>";

  //$query2 = "SELECT name FROM People NATURAL JOIN People_mentioned WHERE idDocument = '" . $row["idDocument"] . "' ORDER BY name";
  $query2 = "SELECT name FROM people WHERE idDocument = '" . $row["idDocument"] . "' ORDER BY name";
  $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
  $num2 = mysqli_num_rows($result2);
  $i2=0;

  while ($i2 < $num2) {
    $row2 = mysqli_result($result2,$i2);

    echo "<p>$row2</p>";

    $i2++;
  }
  echo "</p>";
}

function placesMentioned($row){
  echo "<h3>Places Mentioned:</h3><p>";

  //$query2 = "SELECT place FROM Places NATURAL JOIN Places_mentioned WHERE idDocument = '" . $row["idDocument"] . "' ORDER BY place";
  $query2 = "SELECT place FROM places WHERE idDocument = '" . $row["idDocument"] . "' ORDER BY place";
  $result2 = mysqli_query($GLOBALS["___mysqli_ston"], $query2);
  $num2 = mysqli_num_rows($result2);
  $i2=0;

  while ($i2 < $num2) {
    $row2 = mysqli_result($result2,$i2);

    echo "<p>$row2</p>";

    $i2++;
  }
  echo "</p>";
}

connectToDB();
$result = queryDB();
$letter_body_display = getLetterBodyForDisplay($result);
$letter_body_cloud   = cloud(getLetterBodyForCloud($result), 20);
$citation            = getCitation($result);

$template = new TemplateRenderer();
// Include any variables as an array in the second param
print $template->render('document.html.twig', array(
                        'document' => $result,
                        'letter_body_display' => $letter_body_display,
                        'letter_body_cloud'   => $letter_body_cloud,
                        'citation'            => $citation,
));