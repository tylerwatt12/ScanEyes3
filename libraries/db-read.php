<?php
	function queryUsernameGetUser($username){
		$db = new userDB();
		//CASE INSENSITIVITY!!;
		$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."'");
		return $result->fetchArray();
	}
?>