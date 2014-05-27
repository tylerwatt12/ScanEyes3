<?php
// General includes
#read sqliteDB for variables
include 'includes/head.php'; //<head> tag
include 'includes/header.php'; //include navbar
include 'includes/license.php'; //include license check

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
}elseif ($page == "X") {
	include '';
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