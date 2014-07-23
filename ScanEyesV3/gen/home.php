<?php
include 'libraries/db-write.php';
include 'libraries/db-read.php';
?>
<html>
<body>
<form action="index.php" method="GET">
	<input type="hidden" name="page" value="browse">
	Show calls from: <input type="date" name="browsedate" value="<?php echo date('Y-m-d')?>">
	<input type="submit" value="Browse">
</form>
<h1>or</h1>
<form action="index.php" method="GET">
	<input type="hidden" name="page" value="query">
	Search: <input type="text" name="term" placeholder="TYPE:Query">
	<input type="submit" value="Browse"><br>
	<b>
		TGID: search a talkgroup, e.g. 12345<br>
		RID: search a radioID e.g. 1234567<br>
		TGNAME: search a talkgroup name e.g. North Royalton Fire Dispatch<br>
		RNAME: search a radioID name e.g. Unit 55<br>
		COMMENT search a call comment e.g. Added 2014-05-06<br>
	</b>
</form>