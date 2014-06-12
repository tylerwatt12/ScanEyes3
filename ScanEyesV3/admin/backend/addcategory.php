<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
secReq(3); // Users 3+ can use this page
if (@$_POST['newCategory'] && @$_POST['newColor']) { // If a tag update has been submitted, write to DB (step3)
	
	if (empty($_POST['id'])) {
		$id = "none";
	}else{
		$id = $_POST['id'];
	}
	addSingleCategory($id,$_POST['newCategory'],$_POST['newColor']); //Write to database
}
?>
		<form method="POST">
			<fieldset>
				<legend>Make a category</legend>
				TagID (optional): <input type="number" name="id" min="0" max="99999" title="This can be used to fill in missing category IDs"><br>
				Category name: <input name="newCategory" placeholder="Super extra Special Ops"><br>
				Color: <input type="color" name="newColor">
				<input type="submit" value="Go">
			</fieldset>				
		</form>