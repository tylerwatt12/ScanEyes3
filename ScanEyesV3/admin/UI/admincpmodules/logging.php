<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
if ($_SESSION['usrlvl'] < 3){
	exit();
}
include('admin/backend/admincpmodules/logging.php');
?>
<h2>Security</h2>
<h2>Error</h2>
<h2>Purge</h2>