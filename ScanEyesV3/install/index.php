<?php

if (@!$_GET['instpage']){
	$_GET['instpage'] = 1;
}
include('install/sidebar.php');

include("install/page".$_GET['instpage'].".php");

?>