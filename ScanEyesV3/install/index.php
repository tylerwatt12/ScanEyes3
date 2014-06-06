<?php
if (@!$_GET['step']) {
	$step = 1;
}else{
	$step = $_GET['step'];	
}
include('sidebar.php');
include('page'.$step.'.php');

?>