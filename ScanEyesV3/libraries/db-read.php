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
function getSingleCall($CID){
	global $config;

	$CID = substr($CID, 0,16);
	$CID = SQLite3::escapeString(numOnly($CID)); // Sanitize for SQL in case
	$db = new callsDB(); // Call database instance
	$db->busyTimeout(5000);
	$date = date('Ymd',substr($CID,0,10));
	$dashedDate = date('Y-m-d',substr($CID,0,10));
	$result = $db->query("SELECT * FROM '{$date}' WHERE UNIXTS='{$CID}'"); // Select all the RID INFO from the DB
	unset($db); // unlock database
	$res = $result->fetchArray();
	$tggrab = getSingleTGID($res['TGID']);
	$catgrab = getSingleCat($tggrab['CATEGORY']);
	$ridgrab = getSingleRID($res['RID']);
	// Find file time length (seconds)
	$mp3FileLocal = $config['sccallsavedir'].'/'.$dashedDate.'/'.$CID.$config['sndext']; // Craft the filename to query for length
	if (is_file($mp3FileLocal) == FALSE) {
		growl("error","Could not find call");
		return FALSE;
	}
	$mp3Handle = new mp3file($mp3FileLocal);
	@$length = $mp3Handle->get_metadata()['Length'];
	if (@!$length) {
		growl("error","Internal file error");
		return FALSE;
	}
	// End
	  return array('UNIXTS' => $res['UNIXTS'],
	  			     'TGID' => $res['TGID'],
	 			     'RID' => $res['RID'],
	 			     'DBLENGTH' => $res['LENGTH'],
	 			     'CALCLENGTH' => $length,
	 			     'CALLCOMMENT' => $res['COMMENT'],
	 			     'TGNAME' => $tggrab['NAME'],
	 			     'TGCOMMENT' => $tggrab['COMMENT'],
	 			     'TGCATID' => $tggrab['CATEGORY'],
	 			     'TGCATEGORY' => $catgrab['TAG'],
	 			     'TGCATCOLOR' => $catgrab['COLOR'],
	 			     'RIDCOMMENT' => $ridgrab['COMMENT'],
	 			     'RIDNAME' => $ridgrab['NAME']);
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
function getSingleCat($TAG){
	$TAG = SQLite3::escapeString(numOnly($TAG)); // Sanitize for SQL in case
	$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->query("SELECT * FROM TAG WHERE ID='{$TAG}'"); // Select all the RID INFO from the DB
		unset($db); // unlock database
		$res = $result->fetchArray();
		  return array('ID' => $res['ID'],
		  			     'TAG' => $res['TAG'],
		 			     'COLOR' => $res['COLOR']);
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
function getCallList($TGID,$date,$offset,$list,$sortby,$order){
	#gets every call that happened on a day on a TG offsetted and limited
	$date = SQLite3::escapeString(numOnly(substr($date,0,8))); // Sanitize for date to search
	$TGID = SQLite3::escapeString(numOnly(substr($TGID,0,10))); // Sanitize for TGID
	$offset = SQLite3::escapeString(numOnly(substr($offset,0,8))); // Sanitize page item skip
	$list = SQLite3::escapeString(numOnly(substr($list,0,8))); // Sanitize num of returned items
	$sortby = SQLite3::escapeString(charOnly(substr($sortby,0,8))); // Sanitize sort by (TGID,CID, etc)
	$order = SQLite3::escapeString(charOnly(substr($order,0,4))); // Sanitize order (asc/desc)

	if ($order == "desc" || $order == "asc") { // If user tries modifying asc/desc $_GET
	}else{
		growl("error","unknown error");
		return;
	}
	if ($date>date('Ymd')) { // If date is future return error
		growl("error","Date is the future");
		return;
	}

	$order = strtoupper($order); // Make desc DESC ..etc
	$sortby = strtoupper($sortby); // Make tgid TGID ..etc
	

	$db = new callsDB(); // Call database instance
	$db->busyTimeout(5000);
	$result = $db->query("SELECT * FROM '{$date}' WHERE TGID={$TGID} ORDER BY {$sortby} {$order} LIMIT {$offset},{$list}; "); // Select all the calls DB
	if ($db->lastErrorMsg() !== "not an error") {
		growl("error","No calls for selected day");
		return;
	}
	while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
		if(!isset($res['TGID'])) continue;  //If there are no more values, kill loop
		$returnedArray[$res['UNIXTS']] = array( 'RID' => $res['RID'], 'COMMENT' => $res['COMMENT']);
	}


	
	// Getting the number of total results //
	$rows = $db->query("SELECT COUNT(TGID) as count FROM '{$date}' WHERE TGID={$TGID}; ");
	$row = $rows->fetchArray();
	$numRows = $row['count'];
	if (@!$returnedArray) {
		growl("warning","No calls returned");
		return false;
	}
	#return $countTalkgroups;
	#returns with an array with each talkgroup as key, number of times called as value
	return array('COUNT' => $numRows, 'DATA' => $returnedArray);
}
function getQueryCallList($lastxdays,$query,$offset,$list,$sortby,$order){
	#declare global variables to use
		global $config;

	#sanitize + prepare
		$lastxdays = SQLite3::escapeString(numOnly(substr($lastxdays,0,5))); // Sanitize for date for the last x days to search
		$query = SQLite3::escapeString(substr($query,0,64)); // Sanitize for user submitted query
		$offset = SQLite3::escapeString(numOnly(substr($offset,0,8))); // Sanitize page item skip
		$list = SQLite3::escapeString(numOnly(substr($list,0,8))); // Sanitize num of returned items
		$sortby = SQLite3::escapeString(charOnly(substr($sortby,0,8))); // Sanitize sort by (TGID,CID, etc)
		$order = SQLite3::escapeString(charOnly(substr($order,0,4))); // Sanitize order (asc/desc)
		if ($order == "desc" || $order == "asc") { // If user tries modifying asc/desc $_GET
		}else{
			growl("error","unknown error");
			return;
		}
		$order = strtoupper($order); // Make desc DESC ..etc
		$sortby = strtoupper($sortby); // Make tgid TGID ..etc
	#get every date that calls were recorded on
		$datelist = scandir($config['callsavedir']); // scan call directory for days
		unset ($datelist[0],$datelist[1]); // remove .. and . from array, keep only dates
		foreach ($datelist as $number => $date) { // convert 2014-02-02 to 20140202 for use with SQL query
			$datenodash = numOnly($date);
			$returnDateList[$number] = $datenodash;
		}
		if ($order == "desc") { // sort dates by ascending or descending
			krsort($returnDateList);
		}
		#sample data for $returnDateList
		#type  : array
		#key   : not important
		#value : days to search
		# 20
	#separate the search term from the query
		list($searchFor,$searchQuery) = explode(":", $query);
		$searchQuery = trim($searchQuery);
		$searchFor = trim($searchFor);
		$terms = array('TGID','RID','TGNAME','RNAME','COMMENT'); // loop through available search terms, if not match return false with error
		if (in_array($searchFor, $terms) == FALSE) {
			growl("error","Unknown search term");
			return FALSE;
		}
	#for every day run query
		if ($searchFor == 'TGNAME' || $searchFor == 'RNAME') { // If talkgroup or radio name is searched, translate TGIDname to TGID for search below

		}
		if ($searchFor == 'TGNAME' || $searchFor == 'RNAME' || $searchFor == 'TGID' || $searchFor == 'RID' || $searchFor == 'COMMENT') {
			foreach ($returnDateList as $devnull => $queryDate) {
				$db = new callsDB(); // Call database instance
				$db->busyTimeout(5000);
				$result = $db->query("SELECT * FROM '{$queryDate}' WHERE {$searchFor}={$searchQuery} ORDER BY {$sortby} {$order} LIMIT {$offset},{$list}; "); // Select all the calls DB
				if ($db->lastErrorMsg() !== "not an error") {
					growl("error","No calls for selected day");
					return;
				}
				while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
					if(!isset($res['TGID'])) continue;  //If there are no more values, kill loop
					$returnedArray[$res['UNIXTS']] = array( 'RID' => $res['RID'], 'COMMENT' => $res['COMMENT']);
				}
				// Getting the number of total results //
				$rows = $db->query("SELECT COUNT(TGID) as count FROM '{$queryDate}' WHERE TGID={$TGID}; ");
				$row = $rows->fetchArray();
				$numRows = $numRows+$row['count'];
				if (@!$returnedArray) {
					growl("warning","No calls returned");
					return false;
				}
				#return $countTalkgroups;
			}
		}
		
	
	#returns with an array with each talkgroup as key, number of times called as value
	return array('COUNT' => $numRows, 'DATA' => $returnedArray);
}
?>