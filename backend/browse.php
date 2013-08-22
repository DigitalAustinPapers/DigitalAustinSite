<?php

session_start();

include 'porterStemmer.php';
include '../php/database.php';

print "<h1>Digital Austin Papers Backend</h1>";

$database = connectToDB();

if (array_key_exists('saved', $_GET))
{
    print "<div style='background:#FFFF88; display:inline'>Your changes to {$_GET['saved']} have been saved.</div>";
}

print "<h2>All documents</h2>";
print "<table border=1>";
$result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id, title FROM Document");
while ($row = mysqli_fetch_array($result))
{
    $id = $row['id'];
    $title = $row['title'];
    print "<tr><td><a href='edit.php?id=$id'>$id</a></td><td>$title</td></tr>";
}
print "</table>";

?>

<h2>Upload a new file</h2>
<form action="edit.php" method="post"
enctype="multipart/form-data">
<div style="background:#CCCCFF; display:inline-block">
<label for="file">Filename:</label>
<input type="file" name="teiFile" id="file" /> 
</div>
<br />
<input type="submit" name="submit" value="Submit" />
</form>

