<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
if ($_SESSION['usrlvl'] < 3){
	exit();
}

foreach ($categories as $page => $name) {
	$page = substr($page, 0,-4);
	echo"<a href='?page=admincp&SETTING={$page}'>{$name}</a><br>";
}
?>