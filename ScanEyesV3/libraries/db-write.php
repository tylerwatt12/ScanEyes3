<?php
function updatePwd($username,$prevPass,$newPass,$newPassConf){
	if($newPass !== $newPassConf){ // If newPass and newPassConf don't match throw error
		return "Retyped new passwords don't match";
	}
	$db = new userDB(); // Load up DB
	$db->busyTimeout(5000);
	$result = $db->query("SELECT * FROM USERS WHERE USERNAME='{$username}' COLLATE NOCASE")->fetchArray();
	if ($result == false) { // If user doesn't exist
		return "User doesn't exist";
	}
	if (strlen($newPass) < 5 || strlen($newPass) > 128) { //Password too short/long
		return "Bad password length, 5-128 characters allowed";
	}
	if (password_verify($prevPass, $result['PWD']) == false) { // If submitted, old password doesn't match password in db
		return "Old password is incorrect";
	}
	if (password_verify($newPass, $result['PWD'])) { // If new submitted password matches current one
	 	return "New password matches old password";
	 }
	$username = SQLite3::escapeString($username); // Sanitize for SQL in case
 	$newPassHashed = password_hash($newPass, PASSWORD_BCRYPT); //create new password hash and salt
 	$db->exec("UPDATE USERS SET PWD='{$newPassHashed}' WHERE USERNAME='{$username}'"); //Update password
	if (@$db->lastErrorMsg() !== "not an error") {
		return "There was an error";
	}else{
		unset($_SESSION);
		session_destroy();
		return "password changed";
	}
}
function addUser($regUsername,$regPw,$regEMail,$regLastName,$regFirstName){
	global $config;
	//Filter E-Mail
	//E-Mail contains invalid characters
	$regUsername = strtolower($regUsername); //avoid duplicate DB entries like tylerwatt12 and TylerWatt12
	$regEMail = strtolower($regEMail); //avoid duplicate DB entries like aBC@co.co and ABC@co.co
	if ($regEMail !== filter_var($regEMail, FILTER_SANITIZE_EMAIL)) {
		#Strip ''s?
		return "Bad E-Mail field";
	}
	//E-Mail is too long
	if (strlen($regEMail) > 64) {
		return "Bad E-Mail length, 64 characters maximum.";
	}
	//Filter Username+pw
		//Username contains invalid characters
		if ($regUsername !== preg_replace("/[^a-zA-Z0-9]+/", "", $regUsername)) {
			return "Bad Username characters, use only A-z,0-9 (non case sensitive)";
		}
		//Username is too long
		if (strlen($regUsername) > 32 || strlen($regUsername) < 3) {
			return "Bad Username length, 3-32 chars.";
		}
		//Password too short/long
		if (strlen($regPw) < 5 || strlen($regPw) > 128) {
			return "Bad password length, 5-128 characters";
		}
	//name validation
		//name contains bad characters
		if ($regFirstName !== preg_replace("/[^A-Za-z]/",'',$regFirstName) || 
			$regLastName !== preg_replace("/[^A-Za-z]/",'',$regLastName)) {
			return "Bad Name characters, allowed are A-z.";
		}
		//Name is too long
		if (strlen($regFirstName) > 32 || strlen($regLastName) > 32) {
			return "Bad name length, 32 characters maximum.";
		}
	$regUsername = SQLite3::escapeString($regUsername); // Sanitize for SQL in case
	$regEMail = SQLite3::escapeString( $regEMail ); // Sanitize for SQL in case
	$regFirstName = SQLite3::escapeString( $regFirstName ); // Sanitize for SQL in case
	$regLastName = SQLite3::escapeString( $regLastName ); // Sanitize for SQL in case

	$db = new userDB(); // Call database instance
	$db->busyTimeout(5000);
	$regAuthCode = authGen(32);
	$regShashedPwd = password_hash($regPw, PASSWORD_BCRYPT); //Generate salted hashed password to store into DB
	$query = $db->exec("INSERT INTO USERS (USERNAME,PWD,AUTHCODE,EMAIL,LN,FN,ACCTENABLED,USRLVL,NOTES) 
		VALUES ('{$regUsername}','{$regShashedPwd}','{$regAuthCode}','{$regEMail}','{$regLastName}','{$regFirstName}','0','1','')");
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
	sendAuthEmail($regUsername,$regEMail,$regAuthCode); //email user with auth code
	return "Account created, check your email for a verification code, enter it <a href=\"".$config['httpmethod'].$config['domain']."/?page=auth\">here</a>";
}

function verifyUser($username,$authCode){
	// This function is called when a user opens the checkreg page to enable their account and begin streaming.
	// They must enter their password salt as the auth code, then the DB is written that enables their account.
	global $config;
	$username = charNumOnly($username); // Sanitize it
	$username = SQLite3::escapeString($username);
	$db = new userDB();
	$db->busyTimeout(5000);
	$result = $db->query("SELECT * FROM USERS WHERE USERNAME='{$username}' COLLATE NOCASE"); //Get user info
	$userArray = $result->fetchArray(); //Store result into array
	if($userArray['ACCTENABLED'] == 0){
		//Check verify
		if($authCode == $userArray['AUTHCODE']){
			$db->exec("UPDATE USERS SET ACCTENABLED=1 WHERE USERNAME='{$username}'"); //Enable users account
			if (@$db->lastErrorMsg() !== "not an error") {
				return "There was an error";
			}
			$_SESSION['reputation'] = 5;
			return "Your account has been verified, you may log in now";
		}else{
			sendAuthEmail($userArray['USERNAME'],$userArray['EMAIL'],$userArray['AUTHCODE']); //re-email user with auth code
			return "Incorrect validation code, a code has been resent to your email";
		}
	}else{
		return "Your account is already verified, you may <a href=\"".$config['httpmethod'].$config['domain']."/?page=login\">log in</a>";
	}
}
function saveNotes($username,$notes){
	$notes = SQLite3::escapeString($notes);
	$username = charNumOnly($username); // Clean username
	$username = SQLite3::escapeString($username);
	$db = new userDB();
	$db->busyTimeout(5000);
	$db->exec("UPDATE USERS SET NOTES='{$notes}' WHERE USERNAME='{$username}'"); //Enable users account
	if (@$db->lastErrorMsg() !== "not an error") {
		return "There was an error notes could not be saved";
	}else{
		return "Notes saved successfully";
	}
}
function updateAddTGID($TGID,$newName,$tag){
	// TGID doesn't exist
	// newName 
}
/*	if ($action == "r") {
		$statement .= "DELETE FROM TGRELATE WHERE TGID='{$TGID}'; ";
	}elseif ($action == "m") {
		$statement .= "UPDATE TGRELATE SET NAME='{$newTGIDS[$TGID]['NAME']}',COMMENT='Updated: {$date}',TAG='{$newTGIDS[$TGID]['CATEGORY']}' WHERE TGID='{$TGID}'; ";
	}elseif ($action == "a") {
		$statement .=   "INSERT INTO TGRELATE(TGID, NAME, COMMENT, TAG) VALUES ('{$TGID}', '{$newTGIDS[$TGID]['NAME']}', 'Added: {$date}', '{$newTGIDS[$TGID]['NAME']}'); ";
	}
*/
function updateAddRID($RID,$newName){

}
function updateTag($tagNo,$tagName,$color){

}
function runSQLtalkgroupsDB($statement){
	$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->exec($statement); // Select all the TGID INFO from the DB
		if (@$db->lastErrorMsg() == "not an error") {
			unset($db);
			return "Talkgroups saved";
		}else{
			unset($db);
			return "There was an error: ".$db->lastErrorMsg()."<br>";
		}
}
?>