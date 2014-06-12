<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
secReq(3); // Users 3+ can use this page
if (@$_POST['newName'] && $_POST['TGID'] && $_POST['comment'] && $_POST['tag']) {
	addSingleTGID($_POST['TGID'],$_POST['newName'],$_POST['tag'],$_POST['comment']);
}
$categories = getTGTags();
?>
<form method="POST">
	<fieldset>
		<legend>Adding TG</legend>
		TGID: <input type="number" min="1" max="65535" name="TGID" maxlength="10" placeholder="12345">
		Name: <input type="text" name="newName" maxlength="64" placeholder="Greenville Police Dispatch">
		Comment: <input type="text" name="comment" maxlength="256" value=" ">
		Category: <select name="tag"><?php foreach ($categories as $tagnumber => $catArray) {echo "<option value='{$tagnumber}'>{$catArray['TAG']}</option>";}?></select>
			<input type="submit" value="Add">
	</fieldset>
</form>