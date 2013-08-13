<?php

header('Content-type: text/xml');
  print '<?xml version="1.0" encoding="Windows-1252"?>';
  print "\n";
  print '<?xml-model href="http://www.tei-c.org/release/xml/tei/custom/schema/relaxng/tei_all.rng" schematypens="http://relaxng.org/ns/structure/1.0"?>';
  print "\n";
  print '<?xml-stylesheet type="text/xsl" href="xsl/teibp.xsl"?>';
  print "\n";

?>

<?php include('php/database.php'); ?>
<?php include('php/document.php'); ?>


<?php
	connectToDB();
	$result = queryDB();
	print str_replace('<?xml version="1.0" encoding="Windows-1252"?>', '', $result['xml']);
?>
