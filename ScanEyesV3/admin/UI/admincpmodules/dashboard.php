<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
if ($_SESSION['usrlvl'] < 3){
	exit();
}
include('admin/backend/admincpmodules/dashboard.php');
?>
<h2>Systems check</h2>
<h2>Under Construction mode</h2>
<h2>Recent Searches</h2>
<h2>Call History per month</h2>
<h2>Purge calls older than x</h2>