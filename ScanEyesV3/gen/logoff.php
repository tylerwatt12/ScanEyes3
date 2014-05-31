<?php
include 'libraries/gen-gen.php';
include 'libraries/db-read.php';
if (@!$_SESSION['uid']) {
	echo "You are not logged in";
	exit;
}
unset($_SESSION);
session_destroy();
echo "Logged you off";
?>
