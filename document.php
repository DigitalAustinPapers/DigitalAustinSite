<?php
require_once 'src/TemplateRenderer.class.php';
include_once 'php/database.php';

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

function getLetterBodyForDisplay($row) {
  $doc = getDocXmlFromRow($row);
  $body_node = getLetterBodyNode($row, $doc);
  transformBodyForDisplay($doc, $body_node);
  return $doc->saveXML($body_node);
}

function transformBodyForDisplay($doc, $body) {
  # change people to links
  transformNames($doc, $body, 'persName');
  # change places to links
  transformNames($doc, $body, 'placeName');
}

function transformNames($doc, $body, $tagName) {
  $all_names = $body->getElementsByTagName($tagName);

  while($all_names->length > 0) {
    logString("{$tagName} count={$all_names->length}");
    foreach($all_names as $currName) {
      $reference = $currName->textContent;
      # clean up the reference for text search
      # remove spaces from begining
      $cleaned_reference = preg_replace('/^\s*/', '', $reference);
      # remove spaces from end
      $cleaned_reference = preg_replace('/\s*$/', '', $cleaned_reference);
      # remove newline characters
      $cleaned_reference = preg_replace('/[\n\r]/', ' ', $cleaned_reference);
      #			logString("reference [{$reference}] cleaned to [{$cleaned_reference}]");

      $search_target = urlencode($cleaned_reference);
      $link = $doc->createElement('a', $reference);
      $link->setAttribute('class', "document__{$tagName}");
      $link->setAttribute('href', "search?query={$search_target}");
      #			logString($link->textContent);
      $result = $currName->parentNode->replaceChild($link, $currName);

    }
    $all_names = $body->getElementsByTagName($tagName);
    # continue until they are all transformed
  }
}

connectToDB();
$result = queryDB();
$letter_body_display = getLetterBodyForDisplay($result);
$citation            = getCitation($result);

$template = new TemplateRenderer();
// Include any variables as an array in the second param
print $template->render('document.html.twig', array(
                        'document' => $result,
                        'letter_body_display' => $letter_body_display,
                        'citation'            => $citation,
));