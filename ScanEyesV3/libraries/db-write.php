<?php
function updatePwd($username,$prevPass,$newPass,$newPassConf){
	if($newPass !== $newPassConf){ // If newPass and newPassConf don't match throw error
		return "New passwords don't match";
	}
	
	$db = new userDB(); // Load up DB
	$db->busyTimeout(5000);
	$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."' COLLATE NOCASE")->fetchArray();
	
	if (md5($prevPass.$result['PWSALT']) !== md5($result['PWD'].$result['PWSALT'])) {
		return "Old password is incorrect";
	}else{
		// if oldpassword = oldpassword in DB
		// commence password change
	}

	/* check if password contains correct amnt of chars, no bad chars
	check if password isn't existing
	*/
	$db->exec('CREATE TABLE bar (bar STRING)');
}
function addUser($regUsername,$regPw,$regEMail,$regLastName,$regFirstName){
	//Filter E-Mail
	//E-Mail contains invalid characters
	$regUsername = strtolower($regUsername); //avoid duplicate DB entries like tylerwatt12 and TylerWatt12
	$regEMail = strtolower($regEMail); //avoid duplicate DB entries like aBC@co.co and ABC@co.co
	if ($regEMail !== filter_var($regEMail, FILTER_SANITIZE_EMAIL)) {
		return "Bad E-Mail field";
	}
	//E-Mail is too long
	if (strlen($regEMail) > 32) {
		return "Bad E-Mail length, 32 characters maximum.";
	}

	//Filter Username
	//Username contains invalid characters
	if ($regUsername !== preg_replace("/[^a-zA-Z0-9]+/", "", $regUsername)) {
		return "Bad Username characters, use only A-z,0-9 (non case sensitive)";
	}
	//Username is too long
	if (strlen($regUsername) > 16) {
		return "Bad Username length, 16 characters maximum.";
	}

	//Filter password
	//Password contains invalid characters
	if ($regPw !== filter_var($regPw, FILTER_SANITIZE_EMAIL)) {
		return "Bad password characters Allowed are: letters, digits and !#$%&'*+-/=?^_`{|}~@.[].";
	}
	//Password too short
	if (strlen($regPw) < 5) {
		return "Bad password length, 5 characters maximum.";
	}
	//Password is too long
	if (strlen($regPw) > 64) {
		return "Bad password length, 64 characters maximum.";
	}
	// First name validation
	//First name contains bad characters
	if ($regFirstName !== preg_replace("/[^A-Za-z]/",'',$regFirstName)) {
		return "Bad first name characters, allowed are A-z.";
	}
	//First name is too long
	if (strlen($regFirstName) > 16) {
		return "Bad first name length, 16 characters maximum.";
	}

	// Last name validation
	//Last name contains bad characters
	if ($regLastName !== preg_replace("/[^A-Za-z]/",'',$regLastName)) {
		return "Bad last name characters, allowed are A-z.";
	}
	//Last name is too long
	if ($regLastName !== substr($regLastName, 0, 16)) {
		return "Bad last name length, 16 characters maximum.";
	}
	$db = new userDB(); // Call database instance
	$db->busyTimeout(5000);
	$regPwSalt = saltGen(32); //Generate user specific salt that gets stored into DB
	$regShashedPwd = md5($regPw.$regPwSalt); //Generate salted hashed password to store into DB
	$query = $db->exec("INSERT INTO USERS (USERNAME,PWD,PWSALT,EMAIL,LN,FN,ACCTENABLED,USRLVL,NOTES) VALUES ('{$regUsername}','{$regShashedPwd}','{$regPwSalt}','{$regEMail}','{$regLastName}','{$regFirstName}','0','1','')");
	//HANDLE ERROR
	if ($db->lastErrorMsg() == "column USERNAME is not unique") {
		return "Username exists, Did you forget your password? [password reset link]";
	}
	if ($db->lastErrorMsg() == "column EMAIL is not unique") {
		return "E-Mail exists, Did you forget your password? [password reset link]";
	}
	if ($db->lastErrorMsg() !== "not an error") {
		// other uncaught error
		return "There was an unknown error.".$db->lastErrorMsg();
	}
	sendAuthEmail($regUsername,$regEMail,$regPwSalt); //email user with auth code
	return "Account created, check your email for a verification code";
}

function verifyUser($username,$authCode){
	// This function is called when a user opens the checkreg page to enable their account and begin streaming.
	// They must enter their password salt as the auth code, then the DB is written that enables their account.
	global $config;
	$username = charNumOnly($username); // Sanitize it
	$db = new userDB();
	$db->busyTimeout(5000);
	$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."' COLLATE NOCASE"); //Get user info
	$userArray = $result->fetchArray(); //Store result into array
	if($userArray['ACCTENABLED'] == 0){
		//Check verify
		if($authCode == $userArray['PWSALT']){
			$db->exec("UPDATE USERS SET ACCTENABLED=1 WHERE USERNAME='{$username}'"); //Enable users account
			if (@$db->lastErrorMsg() !== "not an error") {
				return "There was an error";
			}
			$_SESSION['reputation'] = 5;
			return "Your account has been verified, you may log in now";
		}else{
			sendAuthEmail($userArray['USERNAME'],$userArray['EMAIL'],$userArray['PWSALT']); //re-email user with auth code
			return "Incorrect validation code, a code has been resent to your email";
		}
	}else{
		return "Your account is already verified, you may <a href=\"".$config['httpmethod'].$config['domain']."/?page=login\">log in</a>";
	}
}
function saveNotes($username,$notes){
	$notes = htmlspecialchars($notes); // Clean text field
	$username = charNumOnly($username); // Clean username
	$db = new userDB();
	$db->busyTimeout(5000);
	$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."' COLLATE NOCASE");
	return $result->fetchArray();
}
?>