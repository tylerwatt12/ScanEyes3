<?php
/*
function resetPwd($key,$newPass,$newPassConf){
	$newPassConf = SQLite3::escapeString($newPassConf); // Sanitize for SQL in case
	if($newPass !== $newPassConf){ // If newPass and newPassConf don't match throw error
		return "Retyped new passwords don't match";
	}
	$db = new userDB(); // Load up DB
	$db->busyTimeout(5000);
	$result = $db->query("SELECT UID FROM USERS WHERE AUTHCODE='{$key}' COLLATE NOCASE")->fetchArray();
	if ($result == false) { // If user doesn't exist
		growl("error","unspecified error");
		return;
	}
	if (strlen($newPass) < 5 || strlen($newPass) > 128) { //Password too short/long
		gowl("error","Bad password length, 5-128 characters allowed");
		return;
	}
	
 	$newPassHashed = password_hash($newPass, PASSWORD_BCRYPT); //create new password hash and salt
 	$newAuthCode = AuthGen(32);
 	$db->exec("UPDATE USERS SET PWD='{$newPassHashed}',AUTHCODE='{$newAuthCode}' WHERE AUTHCODE='{$key}'"); //Update password
	if (@$db->lastErrorMsg() !== "not an error") {
		growl("error","There was an error");
	}else{
		unset($_SESSION);
		session_destroy();
		growl("notice","password changed");
	}
}
function updateEmail($newEmail){
	$newEmail = SQLite3::escapeString($newEmail); // Sanitize for SQL in case
	$db = new userDB(); // Load up DB
	$db->busyTimeout(5000);
	$db->exec("UPDATE USERS SET EMAIL='{$newEmail}' WHERE UID='{$_SESSION['UID']}'"); //Update password
	if (@$db->lastErrorMsg() !== "not an error") {
		growl("error","There was an error");
	}else{
		growl("notice","E-Mail changed");
	}
}
function updateName($newFN,$newLN){
	$newEmail = SQLite3::escapeString($newEmail); // Sanitize for SQL in case
	$db = new userDB(); // Load up DB
	$db->busyTimeout(5000);
	$db->exec("UPDATE USERS SET FN='{$newFN}',LN='{$newLN}' WHERE UID='{$_SESSION['UID']}'"); //Update password
	if (@$db->lastErrorMsg() !== "not an error") {
		growl("error","There was an error");
	}else{
		growl("notice","name changed");
	}
}
function deleteAccount(){
	$newEmail = SQLite3::escapeString($newEmail); // Sanitize for SQL in case
	$db = new userDB(); // Load up DB
	$db->busyTimeout(5000);
	$db->exec("DELETE FROM USERS WHERE UID = '{$_SESSION['UID']}';"); //Update password
	if (@$db->lastErrorMsg() !== "not an error") {
		growl("error","There was an error");
		return;
	}else{
		unset($_SESSION);
		session_destroy();
		growl("notice","Account deleted, you are now logged out");
		return;
	}
}
*/
?>