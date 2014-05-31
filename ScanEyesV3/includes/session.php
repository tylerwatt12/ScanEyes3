<?php
session_start();
if (@!$_SESSION['usrlvl']) { //If user isn't logged in, grant guest access
	$_SESSION['usrlvl'] = 1;
}
if (@isset($_SESSION['reputation']) == false){
	$_SESSION['reputation'] = 5;
}
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