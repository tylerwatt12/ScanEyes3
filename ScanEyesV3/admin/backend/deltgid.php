<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
secReq(3); // Users 3+ can use this page
$TGIDs = getTGList();
if (@$_POST['confirm'] && @$_POST['TGID']) {
	delTGID($_POST['TGID']);
}elseif(@getSingleTGID($_GET['TGID'])['TGID'] == NULL){
?>
	<form method="POST">
	<fieldset>
		<legend>Multidelete RID</legend>
			<select name="TGID[ ]" multiple>
				<?php foreach ($TGIDs as $TGID => $TGIDArray) {
					echo "<option value='{$TGID}'>{$TGID} {$TGIDArray['NAME']}</option>";
				}?>
				
			</select>
		<input name="confirm" type="hidden" value="yes">
		<input type="submit" value="Delete">
	</fieldset>
</form>
<?php
	exit();
}elseif (@$_GET['TGID']) {
?>

<form method="POST">
	<fieldset>
		<legend>Deleting TGID <?php echo htmlspecialchars($_GET['TGID'])?></legend>
		<input type="checkbox" name="confirm">
		I would like to delete talkgroup: <?php echo htmlspecialchars($_GET['TGID']).", ".htmlspecialchars(getSingleTGID($_GET['TGID'])['NAME']); ?><br>
			<input name="TGID[0]" type="hidden" value="<?php echo htmlspecialchars($_GET['TGID']); ?>">
			<input type="submit" value="Delete">
	</fieldset>
</form>

<?php
}
?>
