<br>
<?php
echo "<a href='index.php'>Home</a><br>";
echo "<a href='?page=viewsystem'>View Database</a><br>";

if (@!$_SESSION['uid']) {
	echo '<a href="?page=login">login</a><br>';
}
if (@$_SESSION['uid']) {
	echo '<a href="?page=logoff">Log-Off</a><br>';
}
if (@!$_SESSION['uid'] && $config['acctcreateenabled'] == "yes") {
	echo '<a href="?page=register">register</a><br>';
}
?>