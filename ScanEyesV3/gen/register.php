<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI']) || $config['acctcreateenabled'] == "no"){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
include 'libraries/gen-gen.php';
if (@$_POST['regUsername'] && $_POST['regPw'] && $_POST['regEMail'] && $_POST['regLastName'] && $_POST['regFirstName']) {
	// register user
	// $regUsername,$regPw,$regEMail,$regLastName,$regFirstName
	echo addUser($_POST['regUsername'],$_POST['regPw'],$_POST['regEMail'],$_POST['regLastName'],$_POST['regFirstName']);
}else{
	// Allow user to create account
	echo '<form method="POST">
			Username: <input type="text" name="regUsername"><br>
			Password: <input type="password" name="regPw"><br>
			E-Mail: <input type="email" name="regEMail"><br>
			First Name: <input type="text" name="regFirstName"><br>
			Last Name: <input type="text" name="regLastName"><br>
			<input type="submit">
	';
}else{
	// Users aren't allowed to create accounts
}

?>