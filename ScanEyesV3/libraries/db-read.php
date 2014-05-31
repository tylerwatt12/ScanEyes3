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
		$password = filter_var($password, FILTER_SANITIZE_EMAIL); // clean password
		$username = preg_replace("/[^a-zA-Z0-9]+/", "", $username); // clean username
		$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."' COLLATE NOCASE");
		unset($db);
		$userArray = $result->fetchArray();
		#return $userArray;
		if (md5($password.$userArray['PWSALT']) == $userArray['PWD'] ) { // If password matches hash
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
?>