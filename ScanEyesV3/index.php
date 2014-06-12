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

}elseif ($page == "importrrtgid") {
	include 'admin/backend/importrrtgid.php';

}elseif ($page == "rrupdatetgid") {
	include 'admin/backend/rrupdatetgid.php';

}elseif ($page == "importuttgid") {
	include 'admin/backend/importuttgid.php';

}elseif ($page == "utupdatetgid") {
	include 'admin/backend/utupdatetgid.php';

}elseif ($page == "viewsystem") {
	include 'gen/viewsystem.php';

}elseif ($page == "edittgid") {
	include 'admin/backend/edittgid.php';

}elseif ($page == "editrid") {
	include 'admin/backend/editrid.php';

}elseif ($page == "editcategory") {
	include 'admin/backend/editcategory.php';

}elseif ($page == "addtgid") {
	include 'admin/backend/addtgid.php';

}elseif ($page == "addrid") {
	include 'admin/backend/addrid.php';

}elseif ($page == "addcategory") {
	include 'admin/backend/addcategory.php';

}elseif ($page == "deltgid") {
	include 'admin/backend/deltgid.php';

}elseif ($page == "delrid") {
	include 'admin/backend/delrid.php';

}elseif ($page == "delcategory") {
	include 'admin/backend/delcategory.php';

}elseif ($page == "logoff") {
	include 'gen/logoff.php';
}
include 'includes/header.php'; //include navbar
include 'includes/footer.php'; //include footer
?>