<?php
//this page gets included on every document KEEP IT SHORT
function charOnly($input){
	// includes letters capital and lowercase else is removed
	return preg_replace("/[^A-Za-z]/", "", $input);
}
function charNumOnly($input){
	// includes letters capital and lowercase else is removed
	return preg_replace("/[^A-Za-z0-9]/", "", $input);
}
function numOnly($input){
	// includes letters capital and lowercase else is removed
	return preg_replace("/[^0-9]/", "", $input);
}
function charNumSymOnly($input){
	// includes alphanumeric and underscore, else is removed
	//MAY NOT WORK RIGHT
	return preg_replace("/^[\w]$/", "", $input);
}
function AuthGen($strength) {
	// Generates salt for password
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $strength; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function secReq($usrlvl){
	global $_SESSION;
	if ($_SESSION['usrlvl'] < $usrlvl) { // If user isn't a DB admin or higher, stop page load
		exit();
	}
}
function sendPwReset($username,$email){
	$email = strtolower($email);
	$username = strtolower($username);
	$sqlusername = SQLite3::escapeString(strtolower($username)); // Sanitize for SQL in case
	$sqlemail = SQLite3::escapeString(strtolower($email)); // Sanitize for SQL in case

	$db = new userDB(); // Load up DB
	$db->busyTimeout(5000);
	$userQuery = $db->query("SELECT UID,EMAIL,AUTHCODE,ACCTENABLED FROM USERS WHERE USERNAME='{$sqlusername}'")->fetchArray();
	if (empty($userQuery['UID'])) {// check if user exists, if doesn't exist
		growl("error","unspecified error1");
		return;
	}elseif ($userQuery['EMAIL'] !== $email) {// query user, check if email matches submitted email
		growl("error","unspecified error2");
		return;
	}elseif ($userQuery['ACCTENABLED'] !== 1) {// query user, check if account is enabled
		sendAuthEmail($username,$email,$userQuery['AUTHCODE']); // resend activation email
		growl("error","Your account is not activated yet, check your email for an activation link");
		return;
	}else{
		$key = authgen(32); // generate new authcode
		$db->exec("UPDATE USERS SET AUTHCODE='{$key}' WHERE USERNAME='{$sqlusername}'"); //write new authcode
		growl("notice",sendResetEmail($email,$key));// send reset email
		return;
	}
}
?>













