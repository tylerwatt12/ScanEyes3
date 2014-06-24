<?php
include 'libraries/db-write.php';
include 'libraries/db-read.php';
?>
<html>
<body>
<form action="index.php" method="GET">
	<input type="hidden" name="page" value="browse">
	<input type="date" name="browsedate" value="<?php echo date('y-m-d')?>">
	<input type="submit" value="Browse">
</form>