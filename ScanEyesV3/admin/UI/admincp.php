<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
if ($_SESSION['usrlvl'] < 3){
	exit();
}
// Define page categories
$categories = array( 'dashboard.php' => 'Dashboard',
					       'apps.php'=> 'Apps',
					   'logging.php' => 'Logs',
					  'settings.php' => 'Settings',
					     'users.php' => 'Users');
if (@!$_GET['SETTING']) {
	$includeSetting = "dashboard.php";
	$humanName = "Dashboard";
}else{
	foreach ($categories as $page => $name) {
		if ($_GET['SETTING'] == substr($page, 0,-4)) {
			$includeSetting = $page;
			$humanName = $name;
		}
	}
}
echo "<h1>".$humanName."</h1>";
#pass POST data through this page, include backend page to process app start/resets
include('admin/ui/sidebar.php');
include('admin/ui/admincpmodules/'.$includeSetting);
#show dashboard
#calls for past week in iframe
#app reset buttons action self method post
?>