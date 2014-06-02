<?php
// General includes
include 'includes/session.php'; // load session activator and reputation set
#session_destroy(); //reset banned users
#function customError($errno, $errstr) {
##  echo "<b>Error:</b> [$errno] $errstr";
#}
#set_error_handler("customError");
include 'includes/classes.php'; // load database class
include 'includes/config.php'; // read variables from database
include 'includes/head.php'; //<head> tag


if ($_SESSION['reputation'] < 1) {
	echo "Please contact the database administrator for assistance. ".$config['globaladminemail'];
	exit();
}
// Page specific libraries
include 'libraries/gen-sec.php';

echo "usrlvl: ".$_SESSION['usrlvl']." rep:".$_SESSION['reputation']."<br>"; //debug
if (@!$_GET['page']) { // If a user just types in the website name without page name
	$page = "home";
}else{
	$page = charOnly($_GET['page']); // If page is specified, clean it
}

if ($page == "home") {
	include 'gen/home.php';

}elseif ($page == "sandbox") {
	include 'gen/sandbox.php';

}elseif ($page == "x") {
	include 'gen/x.php';

}elseif ($page == "x") {
	include 'gen/x.php';

}elseif ($page == "register") {
	include 'gen/register.php';

}elseif ($page == "auth") {
	include 'gen/auth.php';

}elseif ($page == "login") {
	include 'gen/login.php';

}elseif ($page == "importcsv") {
	include 'gen/importcsv.php';

}elseif ($page == "logoff") {
	include 'gen/logoff.php';
}
include 'includes/header.php'; //include navbar
include 'includes/footer.php'; //include footer
?>