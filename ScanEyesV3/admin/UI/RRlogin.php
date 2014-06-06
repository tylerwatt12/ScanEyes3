<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
	echo '<b>Please log into Radioreference</b>
			<form method="POST">
				<input type="text" name="rrdbUsername">
				<input type="password" name="rrdbPassword">
				<input type="submit" value="login">
			</form>';
?>