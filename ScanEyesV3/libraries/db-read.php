<?php
	function queryUsernameGetUser($username){
		$db = new userDB();
		$db->busyTimeout(5000);
		$username = strtolower(preg_replace("/[^a-zA-Z0-9]+/", "", $username)); // clean username
		$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."' COLLATE NOCASE");
		unset($db);
		return $result->fetchArray();
	}
	function checkLogin($username,$password){
		$db = new userDB(); // call user database
		$db->busyTimeout(5000);
		$_SESSION['usrlvl'] = 1; //reset user level
		$username = preg_replace("/[^a-zA-Z0-9]+/", "", $username); // clean username
		$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."' COLLATE NOCASE");
		unset($db);
		$userArray = $result->fetchArray();
		#return $userArray;
		if (password_verify($password, $userArray['PWD'])){ // If password matches hash
			if ($userArray['ACCTENABLED'] == 0) { // If user hasn't activated their account yet
				sendAuthEmail($userArray['USERNAME'],$userArray['EMAIL'],$userArray['PWSALT']);
				return "Your account is not activated yet, an email has been sent to the following address: ".htmlspecialchars($userArray['EMAIL']);
			}
			$_SESSION['usrlvl'] = $userArray['USRLVL'];
			$_SESSION['uid'] = $userArray['UID'];
			$_SESSION['email'] = $userArray['EMAIL'];
			$_SESSION['ln'] = $userArray['LN'];
			$_SESSION['fn'] = $userArray['FN'];
			$_SESSION['acctenabled'] = $userArray['ACCTENABLED'];
			$_SESSION['notes'] = $userArray['NOTES'];
			$_SESSION['reputation'] = 5; // reset reputation
			return "Login successful";
			//Login successful
		}else{
			$_SESSION['reputation']--;
			return "bad username or password, ".$_SESSION['reputation']." tries left.";
		}
	}
	function getTGIDs(){ // Gets all TGIDs from local database into array $locadb[TGID] = name
		$db = new callsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->query("SELECT * FROM TGRELATE"); // Select all the TGIDs from the DB
		while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
			if(!isset($res['TGID'])) continue;  //If there are no more values, kill loop
			$localdb[$res['TGID']] = str_replace("\n", '',$res['NAME']); //Set array, $localdb, key TGID, value TGNAME
		}
		if (isset($localdb)) { //If any values returned
			return $localdb;
		}else{
			return false;
		}
		
	}
	function getRIDs(){ // Gets all RIDs from local database into array $locadb[RID] = name
		$db = new callsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->query("SELECT * FROM RIDRELATE"); // Select all the TGIDs from the DB
		while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
			if(!isset($res['RID'])) continue;  //If there are no more values, kill loop
			$localdb[$res['RID']] = str_replace("\n", '',$res['NAME']); //Set array, $localdb, key TGID, value TGNAME
		}
		if (isset($localdb)) { //If any values returned
			return $localdb;
		}else{
			return false;
		}
	}
	function getTGTags(){
		#########################################
		#	Don't call this function too often	#
		#	Set this function as a variable 	#
		#########################################
		# Output: $tagID[tagnumber] = array(	#
		#			"TAG" => "Police Dispatch", #
		#			"COLOR" => "#0000FF");		#
		#########################################
		/*  Get list of TAGs (PoliceDispatch, Police-Tac, etc) AND SET COLORS FOR TGS */
		$db = new callsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->query("SELECT * FROM TAG"); // Select all the TGIDs from the DB
		unset($db); // unlock database
		while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
			if(!isset($res['ID'])) continue;  //If there are no more values, kill loop
			$tagID[$res['ID']] = array('TAG' => $res['TAG'], 'COLOR' => $res['COLOR']); //Set array,
		}
		return $tagID;
	}
	function getNotes($username){
	$username = charNumOnly($username); // Clean username
	$db = new userDB();
	$db->busyTimeout(5000);
	$result = $db->query("SELECT NOTES FROM USERS WHERE USERNAME='{$username}'"); //query for notes file in user profile
	return $result->fetchArray()['NOTES']; // return notes for specific user
}
?>