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
		growl("error","Bad E-Mail length, 64 characters maximum.");
		return;
	}
	//Filter Username+pw
		//Username contains invalid characters
		if ($regUsername !== preg_replace("/[^a-zA-Z0-9]+/", "", $regUsername)) {
			growl("error","Bad Username characters, use only A-z,0-9 (non case sensitive)");
			return;
		}
		//Username is too long
		if (strlen($regUsername) > 32 || strlen($regUsername) < 3) {
			growl("error","Bad Username length, 3-32 chars.");
			return;
		}
		//Password too short/long
		if (strlen($regPw) < 5 || strlen($regPw) > 128) {
			growl("error","Bad password length, 5-128 characters");
			return;
		}
	//name validation
		//name contains bad characters
		if ($regFirstName !== preg_replace("/[^A-Za-z]/",'',$regFirstName) || 
			$regLastName !== preg_replace("/[^A-Za-z]/",'',$regLastName)) {
			growl("error", "Bad Name characters, allowed are A-z.");
			return;
		}
		//Name is too long
		if (strlen($regFirstName) > 32 || strlen($regLastName) > 32) {
			growl("error","Bad name length, 32 characters maximum.");
			return;
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
	if (strpos($db->lastErrorMsg(), "not unique") !== false) {
		growl("error", "Username exists, Did you forget your password? [password reset link]");
		return;
	}
	if ($db->lastErrorMsg() !== "not an error") {
		// other uncaught error
		growl("error", "There was an unknown error.".$db->lastErrorMsg());
		return;
	}
	if(sendAuthEmail($regUsername,$regEMail,$regAuthCode) == false){
		growl("error", "There was an internal E-Mail error");
		return;
	}else{
		growl("notice","Account created, check your email for a verification code, enter it <a href=\"".$config['httpmethod'].$config['domain']."/?page=auth\">here</a>");
		return;
	}
}

function verifyUser($username,$authCode){
	// This function is called when a user opens the checkreg page to enable their account and begin streaming.
	// They must enter their password salt as the auth code, then the DB is written that enables their account.
	global $config;
	$username = SQLite3::escapeString(charNumOnly($username));
	$db = new userDB();
	$db->busyTimeout(5000);
	$result = $db->query("SELECT * FROM USERS WHERE USERNAME='{$username}' COLLATE NOCASE"); //Get user info
	$userArray = $result->fetchArray(); //Store result into array
	if($userArray['ACCTENABLED'] == 0){
		//Check verify
		if($authCode == $userArray['AUTHCODE']){
			$db->exec("UPDATE USERS SET ACCTENABLED=1 WHERE USERNAME='{$username}'"); //Enable users account
			if (@$db->lastErrorMsg() !== "not an error") {
				growl("error", "There was an error");
				return;
			}
			$_SESSION['reputation'] = 5;
			growl("notice", "Your account has been verified, you may log in now");
			return;
		}else{
			if(sendAuthEmail($userArray['USERNAME'],$userArray['EMAIL'],$userArray['AUTHCODE']) == false){
				growl("error", "There was an internal E-Mail error");
				return;
			}else{
				growl("error", "Incorrect validation code, a code has been resent to your email, enter it <a href=\"".$config['httpmethod'].$config['domain']."/?page=auth\">here</a>");
				return;

			}
		}
	}else{
		growl("notice", "Your account is already verified, you may <a href=\"".$config['httpmethod'].$config['domain']."/?page=login\">log in</a>");
		return;
	}
}
function saveNotes($username,$notes){
	$notes = SQLite3::escapeString($notes);
	$username = SQLite3::escapeString($username);
	$db = new userDB();
	$db->busyTimeout(5000);
	$db->exec("UPDATE USERS SET NOTES='{$notes}' WHERE USERNAME='{$username}'"); //Enable users account
	if (@$db->lastErrorMsg() !== "not an error") {
		growl("error","There was an error notes could not be saved");
		return;
	}else{
		growl("notice","Notes saved successfully");
		return;
	}
}
function placeholderdeleteme($TGID,$newName,$tag){
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
function addSingleTGID($TGID,$newName,$tag,$comment){
	$date = date('Y-m-d');
	$TGID = numOnly($TGID);
	$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->exec("UPDATE TGRELATE SET NAME='{$newName}',COMMENT='Updated: {$date}, {$comment}',TAG='{$tag}' WHERE TGID='{$TGID}'; ");
		unset($db); // unlock database
}
function addSingleRID($RID,$newName,$comment){
	$date = date('Y-m-d');
	$RID = SQLite3::escapeString(numOnly($RID));
	$newName = SQLite3::escapeString(substr($newName,0,128));
	$comment = SQLite3::escapeString(substr($comment,0,64));
	$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->exec("UPDATE RIDRELATE SET NAME='{$newName}',COMMENT='Updated: {$date}, {$comment}' WHERE RID='{$RID}'; ");
		unset($db); // unlock database
}
function addSingleCategory($tag,$newCategory,$newColor){
	$tag = SQLite3::escapeString(numOnly(substr($tag,0,3)));
	$newCategory = SQLite3::escapeString(substr($newCategory,0,64));
	$newColor = SQLite3::escapeString(substr($newColor,0,7));
	$db = new talkgroupsDB(); // Call database instance
	$db->busyTimeout(5000);
	$db->exec("UPDATE TAG SET TAG='{$newCategory}',COLOR='{$newColor}' WHERE ID='{$tag}'; "); 
	unset($db); // unlock database
}

function writeSingleTGID($TGID,$newName,$tag,$comment){
	$date = date('Y-m-d');
	$TGID = numOnly($TGID);
	$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->exec("UPDATE TGRELATE SET NAME='{$newName}',COMMENT='Updated: {$date}, {$comment}',TAG='{$tag}' WHERE TGID='{$TGID}'; ");
		unset($db); // unlock database
}
function writeSingleRID($RID,$newName,$comment){
	$date = date('Y-m-d');
	$RID = SQLite3::escapeString(numOnly($RID));
	$newName = SQLite3::escapeString(substr($newName,0,128));
	$comment = SQLite3::escapeString(substr($comment,0,64));
	$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->exec("UPDATE RIDRELATE SET NAME='{$newName}',COMMENT='Updated: {$date}, {$comment}' WHERE RID='{$RID}'; ");
		unset($db); // unlock database
}
function writeSingleCategory($tag,$newCategory,$newColor){
	$tag = SQLite3::escapeString(numOnly(substr($tag,0,3)));
	$newCategory = SQLite3::escapeString(substr($newCategory,0,64));
	$newColor = SQLite3::escapeString(substr($newColor,0,7));
	$db = new talkgroupsDB(); // Call database instance
	$db->busyTimeout(5000);
	$db->exec("UPDATE TAG SET TAG='{$newCategory}',COLOR='{$newColor}' WHERE ID='{$tag}'; "); 
	unset($db); // unlock database
}
function runSQLtalkgroupsDB($statement){
	$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->exec($statement); // Select all the TGID INFO from the DB
		if (@$db->lastErrorMsg() == "not an error") {
			unset($db);
			growl("notice","Talkgroups saved");
			return;
		}else{
			unset($db);
			growl("error","There was an error: ".$db->lastErrorMsg());
			return;
		}
}
?>