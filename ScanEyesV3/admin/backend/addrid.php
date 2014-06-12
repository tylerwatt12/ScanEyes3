<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
secReq(3); // Users 3+ can use this page
if (@$_POST['newName'] && $_POST['RID'] && $_POST['comment']) {
	addSingleRID($_POST['RID'],$_POST['newName'],$_POST['comment']);
}
?>
<form method="POST">
	<fieldset>
		<legend>Adding TG</legend>
		RID: <input type="number" min="1" max="999999999" name="RID" maxlength="10" placeholder="1878401">
		Name: <input type="text" name="newName" maxlength="64" placeholder="Unit 55">
		Comment: <input type="text" name="comment" maxlength="256" value=" ">
			<input type="submit" value="Add">
	</fieldset>
</form>