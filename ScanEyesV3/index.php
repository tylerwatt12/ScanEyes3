<?php
// General includes
include 'includes/session.php'; // load session activator and reputation set
if (is_dir('install') && is_file('../database/config.sqlite') == FALSE) {
	echo "<a href='./install/'>Please configure ScanEyes first</a>";
	exit();
}
include 'includes/classes.php'; // load database class
include 'includes/config.php'; // read variables from database
include 'includes/head.php'; //<head> tag

// Page specific libraries
include 'libraries/gen-sec.php';
include 'libraries/gen-gen.php';

if (is_dir('install') && is_file('../database/config.sqlite') == TRUE) {
	#growl("notice","install directory was found and removed");
	#rrmdir('install'); // If database exists, but install dir also exists, remove install dir
}

echo "usrlvl: ".$_SESSION['usrlvl']." rep:".$_SESSION['reputation']."<br>"; //debug
if (@!$_GET['page']) { // If a user just types in the website name without page name
	$page = "home";
}else{
	$page = charOnly($_GET['page']); // If page is specified, clean it
}
$pages = array('home' => 'gen/home.php', // Main page that's called when website is loaded
	'logoff' => 'gen/logoff.php',
	'pwreset' => 'user/backend/pwreset.php',
	'tgid' => 'gen/tgid.php',
	'browse' => 'gen/browse.php',
	'delcategory' => 'admin/backend/delcategory.php',
	'delrid' => 'admin/backend/delrid.php',
	'deltgid' => 'admin/backend/deltgid.php',
	'addcategory' => 'admin/backend/addcategory.php',
	'addrid' => 'admin/backend/addrid.php',
	'addtgid' => 'admin/backend/addtgid.php',
	'editcategory' => 'admin/backend/editcategory.php',
	'editrid' => 'admin/backend/editrid.php',
	'edittgid' => 'admin/backend/edittgid.php',
	'viewsystem' => 'gen/viewsystem.php',
	'utupdaterid' => 'admin/backend/utupdaterid.php',
	'utupdatetgid' => 'admin/backend/utupdatetgid.php',
	'importutrid' => 'admin/backend/importutrid.php',
	'importuttgid' => 'admin/backend/importuttgid.php',
	'rrupdatetgid' => 'admin/backend/rrupdatetgid.php',
	'importrrtgid' => 'admin/backend/importrrtgid.php',
	'login' => 'gen/login.php',
	'auth' => 'gen/auth.php',
	'register' => 'gen/register.php',
	'sandbox' => 'gen/sandbox.php' // Test page
);
if (@$pages[$page]) {
	include($pages[$page]);
}else{ // 404
 include('static/404.php');
}


include 'includes/header.php'; //include navbar
include 'includes/footer.php'; //include footer
?>















