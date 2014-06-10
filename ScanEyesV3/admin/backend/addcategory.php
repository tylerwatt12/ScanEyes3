<?php
///		THIS FILE NEEDS TO BE FILLED IN
	if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
		exit();
	}
	include 'libraries/db-write.php';
	include 'libraries/db-read.php';
	secReq(3); // Users 3+ can use this page
	if (@$_POST['newCategory'] && @$_POST['newColor']) { // If a tag update has been submitted, write to DB (step3)
		$newCategory = substr(SQLite3::escapeString($_POST['newCategory']),0,256); // Clean tag text
		$newColor = substr(SQLite3::escapeString($_POST['newColor']),0,7); // Clean color
		$tag = numOnly($_POST['tag']);
		writeSingleCategory($tag,$newCategory,$newColor); //Write to database
		growl("notice","Update completed");
		exit();
	}elseif(@$_POST['tag']){ // If a tag has been chose, fetch tag info (step2)
		$category = getTGTags()[numOnly($_POST['tag'])];
?>
		<form method="POST">
			<fieldset>
				<legend>Pick a category</legend>
				New Name:<input type="text" name="newCategory" value="<?php echo $category['TAG']; ?>">
				<input type="color" name="newColor" value="<?php echo $category['COLOR']; ?>">
				<input name="tag" type="hidden" value="<?php echo htmlspecialchars($_POST['tag']); ?>">
				<input type="submit" value="Update">
			</fieldset>
		</form>
<?php
	}elseif (@!$_POST) { // If no info was submitted, fetch every tag, allow user to choose (step1)
		$categories = getTGTags() // Get every category
?>
		<form method="POST">
			<fieldset>
				<legend>Pick a category</legend>
				Category: <select name="tag">
				<?php foreach ($categories as $tagnumber => $catArray) { echo "<option value='{$tagnumber}'>{$catArray['TAG']}</option>"; } ?></select>
				<input type="submit" value="Go">
			</fieldset>				
		</form>
<?php
	}
?>
