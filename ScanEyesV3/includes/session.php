<?php
session_start();
#session_destroy(); //reset banned users
if (@!$_SESSION['usrlvl']) { //If user isn't logged in, grant guest access
	$_SESSION['usrlvl'] = 1;
}
if (@isset($_SESSION['reputation']) == false){
	$_SESSION['reputation'] = 5;
}
if ($_SESSION['reputation'] < 1) {
	echo "Please contact the administrator for assistance. ";
	exit();
}
function customError($errno, $errstr) {
 # echo "<b>Error:</b> [$errno] $errstr";
}
if ($_SESSION['usrlvl'] < 4) { // Don't handle errors if user isn't admin
	set_error_handler("customError");
}
#
#If

/*

$_SESSION['usrlvl'] = $userArray['USRLVL'];
$_SESSION['uid'] = $userArray['UID'];
$_SESSION['email'] = $userArray['EMAIL'];
$_SESSION['ln'] = $userArray['LN'];
$_SESSION['fn'] = $userArray['FN'];
$_SESSION['acctenabled'] = $userArray['ACCTENABLED'];
$_SESSION['notes'] = $userArray['NOTES'];
$_SESSION['reputation'] = 5; // reset reputation

*/
?>