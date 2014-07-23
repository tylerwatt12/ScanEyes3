<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
if ($_SESSION['usrlvl'] < 3){
	exit();
}
include('admin/backend/admincpmodules/settings.php');
?>
<h2>Copy all tables from setup import here</h2>