<?php
//this page gets included on every document KEEP IT SHORT
function charOnly($input){
	// includes letters capital and lowercase else is removed
	return preg_replace("/[^A-Za-z]/", "", $input);
}
function charNumOnly($input){
	// includes letters capital and lowercase else is removed
	return preg_replace("/[^A-Za-z0-9]/", "", $input);
}
function numOnly($input){
	// includes letters capital and lowercase else is removed
	return preg_replace("/[^0-9]/", "", $input);
}
function charNumSymOnly($input){
	// includes alphanumeric and underscore, else is removed
	//MAY NOT WORK RIGHT
	return preg_replace("/^[\w]$/", "", $input);
}
function AuthGen($strength) {
	// Generates salt for password
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $strength; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function secReq($usrlvl){
	global $_SESSION;
	if ($_SESSION['usrlvl'] < $usrlvl) { // If user isn't a DB admin or higher, stop page load
		exit();
	}
}
?>