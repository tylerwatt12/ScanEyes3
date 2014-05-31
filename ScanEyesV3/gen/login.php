<?php
include 'libraries/gen-gen.php';
include 'libraries/db-read.php';
if ($_SESSION['usrlvl'] > 1) {
	echo "You are already logged in";
	exit;
}
if (@$_POST['username'] && $_POST['password']) {
	echo checkLogin($_POST['username'],$_POST['password']);
}else{
// Allow user to login
echo '<form method="POST">
		Username: <input type="text" name="username"><br>
		Password: <input type="password" name="password"><br>
		<input type="submit">
';
}
?>
