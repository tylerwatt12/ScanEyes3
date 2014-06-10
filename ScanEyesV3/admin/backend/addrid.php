<?php
/// THIS FILE NEEDS TO BE FILLED IN
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
secReq(3); // Users 3+ can use this page
if ($_POST) {
	$newName = SQLite3::escapeString($_POST['newName']);
	$comment = SQLite3::escapeString($_POST['comment']);
	$RID = numOnly($_POST['RID']);
	writeSingleRID($RID,$newName,$comment);
	growl("notice","Update completed");

}elseif(@getSingleRID($_GET['RID'])['RID'] == NULL){
	growl("warning","No such RID");
	exit();
}
$sanRID = numOnly($_GET['RID']);
$RID = getSingleRID($_GET['RID']);
?>
<form method="POST">
	<fieldset>
		<legend>Editing RID <?php echo htmlspecialchars($sanRID.", ".$RID['NAME']); ?></legend>
		Name: <input type="text" name="newName" maxlength="128" value="<?php echo htmlspecialchars($RID['NAME']); ?>">
		Comment: <input type="text" name="comment" maxlength="256" value="<?php echo htmlspecialchars($RID['COMMENT']); ?>">
			<input type="hidden" name="RID" value="<?php echo htmlspecialchars($sanRID); ?>">
			<input type="submit" value="update">
	</fieldset>
						
</form>