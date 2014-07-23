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
/*
		Below shows the various pages in Scaneyes and their functions
*/
$pages = array('home' => 'gen/home.php', // Main page that's called when website is loaded
	'logoff' => 'gen/logoff.php', // An authenticated user can log off here
	'pwreset' => 'user/backend/pwreset.php', // An unauthenticated user can reset their passowrd here
	'tgid' => 'gen/tgid.php', // A user can use this page to view and play all the calls on a date(directed to page from below)
	'playcall' => 'gen/playcall.php', // When a user clicks on a call, this page formats the audio in an html5 audio player with info
	'browse' => 'gen/browse.php', // A user can use this page to see all the talkgroups that were called on a date
	'query' => 'gen/query.php', // A user can type a query string and get results based on that
	'delcategory' => 'admin/backend/delcategory.php', // A DBadmin+ can delete categories in this page
	'delrid' => 'admin/backend/delrid.php', // A DBadmin+ can delete radioids in this page
	'deltgid' => 'admin/backend/deltgid.php',  // A DBadmin+ can delete talkgroups in this page
	'addcategory' => 'admin/backend/addcategory.php',  // A DBadmin+ can add talkgroup categories in this page
	'addrid' => 'admin/backend/addrid.php', // A DBadmin+ can add radioids in this page
	'addtgid' => 'admin/backend/addtgid.php', // A DBadmin+ can add talkgroups in this page
	'editcategory' => 'admin/backend/editcategory.php', // A DBadmin+ can edit talkgroup categories in this page
	'editrid' => 'admin/backend/editrid.php', // A DBadmin+ can edit radioids in this page
	'edittgid' => 'admin/backend/edittgid.php', // A DBadmin+ can edit talkgroups in this page
	'viewsystem' => 'gen/viewsystem.php', // A page anyone can view to see a who systems talkgroups and RadioIDS
	'utupdaterid' => 'admin/backend/utupdaterid.php', // backend page for updating unitrunker radioids
	'utupdatetgid' => 'admin/backend/utupdatetgid.php', // backend page for updating unitrunker talkgroups
	'importutrid' => 'admin/backend/importutrid.php', // A DBadmin+ can add radioids from Unitrunker
	'importuttgid' => 'admin/backend/importuttgid.php', // A DBadmin+ can add talkgroups from Unitrunker
	'rrupdatetgid' => 'admin/backend/rrupdatetgid.php', // backend page for updating radioreference talkgroups
	'importrrtgid' => 'admin/backend/importrrtgid.php', // A DBadmin+ can add talkgroups from radioreference
	'admincp' => 'admin/UI/admincp.php', // An admin can access this page for dashboard statistics
	'login' => 'gen/login.php', // Page where a user can log into scaneyes
	'auth' => 'gen/auth.php', // Page a user is sent to activate their account with code
	'register' => 'gen/register.php', // Where users come to register
	'sandbox' => 'gen/sandbox.php' // Test page
);
if (@$pages[$page]) {
	include($pages[$page]);
}elseif($page = "home"){
	include 'gen/home.php';
}else{ // 404
 include('static/404.php');
}


include 'includes/header.php'; //include navbar
include 'includes/footer.php'; //include footer
?>