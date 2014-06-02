<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
include 'libraries/gen-gen.php';
if (@!$_SESSION['uid']) {
	echo "You are not logged in";
	exit;
}
unset($_SESSION);
session_destroy();
echo "Logged you off";
?>
