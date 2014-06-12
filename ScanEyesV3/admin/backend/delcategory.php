<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
secReq(3); // Users 3+ can use this page
if (@$_POST['CAT']) {
	delCategory($_POST['CAT']);
}else{
	$categories = getTGTags() // Get every category
?>
	<form method="POST">
	<fieldset>
		<legend>Multidelete RID</legend>
			<select name="CAT[ ]" multiple>
				<?php foreach ($categories as $tagnumber => $catArray) { echo "<option value='{$tagnumber}'>{$catArray['TAG']}</option>"; } ?>
				
			</select>
		<input name="confirm" type="hidden" value="yes">
		<input type="submit" value="Delete">
	</fieldset>
</form>
<?php
	}
?>
