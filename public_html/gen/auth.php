<?php
include 'libraries/gen-gen.php';
include 'libraries/db-write.php';
if (@$_GET['code'] && @$_GET['username'] || @$_POST['code'] && @$_POST['username']) {
	if (@$_GET['code']) {
		$code = htmlspecialchars(charNumOnly($_GET['code']));
	}elseif (@$_POST['code']) {
		$code = htmlspecialchars(charNumOnly($_POST['code']));
	}
	if (@$_GET['username']) {
		$username = htmlspecialchars(filter_var($_GET['username'], FILTER_SANITIZE_EMAIL));
	}elseif (@$_POST['username']) {
		$username = htmlspecialchars(filter_var($_POST['username'], FILTER_SANITIZE_EMAIL));
	}
	echo verifyUser($username,$code);
}else{
	//display auth form
	echo '<form method="POST">
			Paste activation code: <input type="text" name="code"><br>
			Username: <input type="text" name="username"><br>
			<input type="submit">
		  </form>';
}



?>