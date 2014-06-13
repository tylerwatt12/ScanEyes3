<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
?>
<html>
<body>
<form action="index.php" method="GET">
	<input type="hidden" name="page" value="browse">
	<input type="date" name="browsedate">
	<input type="submit" value="Browse">
</form>