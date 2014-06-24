<?php
function queryUsernameGetUser($username){
	$username = SQLite3::escapeString(charNumOnly($username)); // Sanitize for SQL in case
	$db = new userDB();
	$db->busyTimeout(5000);
	$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."' COLLATE NOCASE");
	unset($db);
	return $result->fetchArray();
}
function checkLogin($username,$password){
	$username = SQLite3::escapeString(charNumOnly($username)); // Sanitize for SQL in case
	$db = new userDB(); // call user database
	$db->busyTimeout(5000);
	$_SESSION['usrlvl'] = 1; //reset user level
	$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."' COLLATE NOCASE");
	unset($db);
	$userArray = $result->fetchArray();
	#return $userArray;
	if (password_verify($password, $userArray['PWD'])){ // If password matches hash
		if ($userArray['ACCTENABLED'] == 0) { // If user hasn't activated their account yet
			sendAuthEmail($userArray['USERNAME'],$userArray['EMAIL'],$userArray['PWSALT']);
			growl("notice","Your account is not activated yet, an email has been sent to the following address: ".htmlspecialchars($userArray['EMAIL']));
			return;
		}
		$_SESSION['usrlvl'] = $userArray['USRLVL'];
		$_SESSION['uid'] = $userArray['UID'];
		$_SESSION['email'] = $userArray['EMAIL'];
		$_SESSION['ln'] = $userArray['LN'];
		$_SESSION['fn'] = $userArray['FN'];
		$_SESSION['acctenabled'] = $userArray['ACCTENABLED'];
		$_SESSION['notes'] = $userArray['NOTES'];
		$_SESSION['reputation'] = 5; // reset reputation
		growl("notice","Login successful");
		return;
		//Login successful
	}else{
		$_SESSION['reputation']--;
 		growl("warning","bad username or password, ".$_SESSION['reputation']." tries left.");
		return;
	}
}
function getTGTags(){
	#########################################
	#	      call this function once    	#
	#########################################
	# Output: $tagID[tagnumber] = array(	#
	#			"TAG" => "Police Dispatch", #
	#			"COLOR" => "#0000FF");		#
	#########################################
	/*  Get list of TAGs (PoliceDispatch, Police-Tac, etc) AND SET COLORS FOR TGS */
	$db = new talkgroupsDB(); // Call database instance
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
	$username = SQLite3::escapeString(charNumOnly($username)); // Sanitize for SQL in case
	$db = new userDB();
	$db->busyTimeout(5000);
	$result = $db->query("SELECT NOTES FROM USERS WHERE USERNAME='{$username}'"); //query for notes file in user profile
	return $result->fetchArray()['NOTES']; // return notes for specific user
}
function getTGList(){
	################################################
	################################################
	#			GET TGS FROM LOCAL DB 			   #
	################################################
	################################################
		$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->query("SELECT * FROM TGRELATE"); // Select all the TGID INFO from the DB
		unset($db); // unlock database
		while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
		 if(!isset($res['TGID'])) continue;  //If there are no more values, kill loop
			 /*
			 [56055]=>
			  array(3) {
			    ["NAME"]=>
			    string(56) "Cleveland Hopkins International Airport (CLE) Electrical"
			    ["CATEGORY"]=>
			    int(14)
			    ["COMMENT"]=>
			    string(19) "Updated: 2014-06-06"
			  }
			*/
		  $curTGID[$res['TGID']] = array('NAME' => $res['NAME'],
		  								"CATEGORY" => $res['TAG'],
		  								"COMMENT" => $res['COMMENT']);
		}
		@ksort($curTGID);
		return $curTGID;
	}
function getRList(){
	################################################
	################################################
	#			GET RIDS FROM LOCAL DB 			   #
	################################################
	################################################
		$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->query("SELECT * FROM RIDRELATE"); // Select all the RID INFO from the DB
		unset($db); // unlock database
		while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
		 if(!isset($res['RID'])) continue;  //If there are no more values, kill loop
			 /*
			[1823165]=>
			  array(2) {
			    ["NAME"]=>
			    string(14) "Mike Masterson"
			    ["COMMENT"]=>
			    string(17) "Added: 2014-06-06"
			  }
			*/
		  $curTGID[$res['RID']] = array('NAME' => $res['NAME'],
		  								"COMMENT" => $res['COMMENT']);
		}
		@ksort($curTGID);
		return $curTGID;
	}
function getSingleTGID($TGID){
	$TGID = SQLite3::escapeString(numOnly($TGID)); // Sanitize for SQL in case
	$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->query("SELECT * FROM TGRELATE WHERE TGID='{$TGID}'"); // Select all the RID INFO from the DB
		unset($db); // unlock database
		$res = $result->fetchArray();
		  return array('TGID' => $res['TGID'],
		  			     'NAME' => $res['NAME'],
		 			     'COMMENT' => $res['COMMENT'],
		 			     'CATEGORY' => $res['TAG']);
}
function getSingleRID($RID){
	$RID = SQLite3::escapeString(numOnly($RID)); // Sanitize for SQL in case
	$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->query("SELECT * FROM RIDRELATE WHERE RID='{$RID}'"); // Select all the RID INFO from the DB
		unset($db); // unlock database
		$res = $result->fetchArray();
		  return array('RID' => $res['RID'],
		  			     'NAME' => $res['NAME'],
		 			     'COMMENT' => $res['COMMENT']);
}
function getDateCalls($date){
	#Give this function a date without hyphens e.g. 20140613 and it will show you all the talkgroups that played that day, and how many times each
	$date = SQLite3::escapeString(numOnly(substr($date,0,8))); // Sanitize for SQL in case
	if ($date>date('Ymd')) { // If date is future return error
		growl("error","Date is the future");
		return;
	}
	$db = new callsDB(); // Call database instance
	$db->busyTimeout(5000);
	$result = $db->query("SELECT TGID FROM '{$date}'; "); // Select all the calls DB
	if ($db->lastErrorMsg() !== "not an error") {
		growl("error","No calls for selected day");
		return;
	}
	while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
		if(!isset($res['TGID'])) continue;  //If there are no more values, kill loop
			if (@!$countTalkgroups[$res['TGID']]) { // If counter doesn't exist
				$countTalkgroups[$res['TGID']] = 0; //create conuter
			}
			$countTalkgroups[$res['TGID']]++; // count up one	
	}
	arsort($countTalkgroups);
	return $countTalkgroups;
	#returns with an array with each talkgroup as key, number of times called as value
}
function getCallList($TGID,$date,$offset,$list){
	#gets every call that happened on a day on a TG offsetted and limited
	$date = SQLite3::escapeString(numOnly(substr($date,0,8))); // Sanitize for SQL in case
	$TGID = SQLite3::escapeString(numOnly(substr($TGID,0,10))); // Sanitize for SQL in case
	$offset = SQLite3::escapeString(numOnly(substr($offset,0,8))); // Sanitize for SQL in case
	$list = SQLite3::escapeString(numOnly(substr($list,0,8))); // Sanitize for SQL in case

	if ($date>date('Ymd')) { // If date is future return error
		growl("error","Date is the future");
		return;
	}
	$db = new callsDB(); // Call database instance
	$db->busyTimeout(5000);
	$result = $db->query("SELECT * FROM '{$date}' WHERE TGID={$TGID} ORDER BY UNIXTS ASC LIMIT {$offset},{$list}; "); // Select all the calls DB
	if ($db->lastErrorMsg() !== "not an error") {
		growl("error","No calls for selected day");
		return;
	}
	while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
		if(!isset($res['TGID'])) continue;  //If there are no more values, kill loop
		$returnedArray[$res['UNIXTS']] = array( 'RID' => $res['RID'], 'COMMENT' => $res['COMMENT']);
	}
	krsort($returnedArray);
	// Getting the number of total results //
	$rows = $db->query("SELECT COUNT(TGID) as count FROM '{$date}' WHERE TGID={$TGID}; ");
	$row = $rows->fetchArray();
	$numRows = $row['count'];
	#return $countTalkgroups;
	#returns with an array with each talkgroup as key, number of times called as value
	return array('COUNT' => $numRows, 'DATA' => $returnedArray);
}
?>