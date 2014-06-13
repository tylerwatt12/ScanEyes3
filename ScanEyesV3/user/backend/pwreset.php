<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}elseif (@$_SESSION['uid']) {
	exit(); // User can't reset password while logged in
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
if (@$_GET['key'] && @!$_POST['key'] && @!$_POST['newPass'] && @!$_POST['newPassConf']) { // If a user is resetting their password and they clicked on the email link give them a reset form
	$key = htmlspecialchars($_GET['key']);
	echo"<form method='POST'>
			New password: <input type='password' name='newPass'>
			Confirm: <input type='password' name='newPassConf' max='128'>
			<input type='hidden' name='key' value='{$key}'>
			<input type='submit' value='change password'>
		</form>
	";
}elseif(@$_POST['key'] && @$_POST['newPass'] && @$_POST['newPassConf']){
 resetPwd($_POST['key'],$_POST['newPass'],$_POST['newPassConf']);

}elseif(@$_POST['step1email'] && @$_POST['step1username']){ // If a user just submitted the form below, check creds then send recovery email
	sendPwReset($_POST['step1username'],$_POST['step1email']);
}else{ // If a user just visited the page and needs to reset their password
	//check 
	echo"<form method='POST'>
			Email: <input type='email' name='step1email'><Br>
			Username: <input type='text' name='step1username'>
			<input type='submit' value='I forgot my password'>
		</form>";
}
?>
