<?php
/// 						THIS FILE NEEDS TO BE FILLED IN
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
secReq(3); // Users 3+ can use this page
if ($_POST) {
	$newName = SQLite3::escapeString($_POST['newName']);
	$comment = SQLite3::escapeString($_POST['comment']);
	$TGID = numOnly($_POST['TGID']);
	$tag = numOnly($_POST['tag']);

	writeSingleTGID($TGID,$newName,$tag,$comment);
	growl("notice","Update completed");

}elseif(@getSingleTGID($_GET['TGID'])['TGID'] == NULL){
	growl("warning","No such TGID");
	exit();
}
$sanTGID = numOnly($_GET['TGID']);
$TGID = getSingleTGID($_GET['TGID']);
$categories = getTGTags()
?>
<form method="POST">
	<fieldset>
		<legend>Editing TG <?php echo $sanTGID.", ".$TGID['NAME']; ?></legend>
		Name: <input type="text" name="newName" maxlength="128" value="<?php echo $TGID['NAME']; ?>">
		Comment: <input type="text" name="comment" maxlength="256" value="<?php echo $TGID['COMMENT']; ?>">
		Category: <select name="tag"><?php 
			foreach ($categories as $tagnumber => $catArray) {
				if ((string)$TGID['CATEGORY'] == (string)$tagnumber) {
					echo "<option value='{$tagnumber}' selected>{$catArray['TAG']}</option>";
				}else{

					echo "<option value='{$tagnumber}'>{$catArray['TAG']}</option>";
				}
				
			}?></select>
			<input type="hidden" name="TGID" value="<?php echo $sanTGID; ?>">
			<input type="submit" value="update">
	</fieldset>
						
</form>