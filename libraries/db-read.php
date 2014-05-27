<?php
	function queryUsernameGetUser($username){
		class MyDB extends SQLite3
		{
		    function __construct()
		    {
		        $this->open('../userdb.sqlite');
		    }
		}
		$db = new MyDB();
		$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."'");
		return $result->fetchArray();
	}
?>