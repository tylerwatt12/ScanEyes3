<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
	echo '<b>Please upload an XML file</b>
			<form method="POST" enctype="multipart/form-data">
				<input type="file" name="xml" size="50" />
				<input type="submit" value="Upload">
			</form>';
?>