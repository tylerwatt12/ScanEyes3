<?php
	function updateUser(){
		class MyDB extends SQLite3{
		    function __construct(){
		        $this->open('../users.sqlite');
		    }
		}
		$db = new MyDB();
		$result = $db->query("SELECT * FROM USERS WHERE USERNAME='".charNumOnly($username)."'");
		return $result->fetchArray();
		$db->exec('CREATE TABLE bar (bar STRING)');
	}
	function addUser($regUsername,$regPwd,$regPwSalt,$regEMail,$regLastName,$regFirstName){
		class MyDB extends SQLite3{
		    function __construct(){
		        $this->open('../users.sqlite');
		    }
		}
		/*
		check if username has correct amnt of chars, contains no bad chars
		check if first name has correct amnt of chars, contains no bad chars
		check if email is an email, contains correct amnt of chars, no bad chars
		check if password contains correct amnt of chars, no bad chars


		*/
		$db = new MyDB();
		$result = $db->exec("INSERT INTO USERS (USERNAME,PWD,PWSALT,EMAIL,LN,FN,ACCTENABLED,USRLVL,NOTES) VALUES ('{$regUsername}','{$regPwd}','{$regPwSalt}','{$regEMail}','{$regLastName}','{$regFirstName}','0','1','')");
		return $result->fetchArray();
		#email user with auth code
	}
?>