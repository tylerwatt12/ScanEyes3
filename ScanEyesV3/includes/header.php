<br>
<?php
if (@!$_SESSION['uid']) {
	echo '<a href="?page=login">login</a><br>';
}
if (@$_SESSION['uid']) {
	echo '<a href="?page=logoff">Log-Off</a><br>';
}
if (@!$_SESSION['uid']) {
	echo '<a href="?page=register">register</a><br>';
}
?>