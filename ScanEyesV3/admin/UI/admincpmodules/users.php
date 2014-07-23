<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
if ($_SESSION['usrlvl'] < 3){
	exit();
}
include('admin/backend/admincpmodules/users.php');
?>
<h2>Select user</h2>
<h3>Disable, Delete, Add, Promote, Demote</h3>