<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
secReq(3); // Users 3+ can use this page
$RIDs = getRList();
if (@$_POST['confirm'] && @$_POST['RID']) {
	delRID($_POST['RID']);
}elseif(@getSingleRID($_GET['RID'])['RID'] == NULL){
?>
	<form method="POST">
	<fieldset>
		<legend>Multidelete RID</legend>
			<select name="RID[ ]" multiple>
				<?php foreach ($RIDs as $RID => $RIDArray) {
					echo "<option value='{$RID}'>{$RID} {$RIDArray['NAME']}</option>";
				}?>
				
			</select>
		<input name="confirm" type="hidden" value="yes">
		<input type="submit" value="Delete">
	</fieldset>
</form>
<?php
	exit();
}elseif (@$_GET['RID']) {
?>

<form method="POST">
	<fieldset>
		<legend>Deleting RID <?php echo htmlspecialchars($_GET['RID'])?></legend>
		<input type="checkbox" name="confirm">
		I would like to delete talkgroup: <?php echo htmlspecialchars($_GET['RID']).", ".htmlspecialchars(getSingleRID($_GET['RID'])['NAME']); ?><br>
			<input name="RID[0]" type="hidden" value="<?php echo htmlspecialchars($_GET['RID']); ?>">
			<input type="submit" value="Delete">
	</fieldset>
</form>

<?php
}
?>
