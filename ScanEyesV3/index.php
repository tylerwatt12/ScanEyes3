<?php
// General includes
include 'includes/session.php'; // load session activator and reputation set
include 'includes/classes.php'; // load database class
include 'includes/config.php'; // read variables from database
include 'includes/head.php'; //<head> tag

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

}elseif ($page == "importrr") {
	include 'gen/importrr.php';

}elseif ($page == "logoff") {
	include 'gen/logoff.php';
}
include 'includes/header.php'; //include navbar
include 'includes/footer.php'; //include footer
?>