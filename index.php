<?php
session_start();
// General includes
include 'includes/session.php'; // load session activator
include 'includes/classes.php'; // load database class
include 'includes/config.php'; // read variables from database
include 'includes/head.php'; //<head> tag
include 'includes/header.php'; //include navbar

// Page specific libraries
include 'libraries/gen-sec.php';


if (@!$_GET['page']) {
	$page = "home";
}else{
	$page = charOnly($_GET['page']);
}

if ($page == "home") {
	include 'gen/home.php';
}elseif ($page == "sandbox") {
	include 'gen/sandbox.php';
}elseif ($page == "register") {
	include 'gen/register.php';
}elseif ($page == "X") {
	include '';
}elseif ($page == "X") {
	include '';
}elseif ($page == "X") {
	include '';
}elseif ($page == "X") {
	include '';
}
include 'includes/footer.php'; //include footer
?>