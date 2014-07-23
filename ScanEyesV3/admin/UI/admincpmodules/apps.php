<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
if ($_SESSION['usrlvl'] < 3){
	exit();
}
include('admin/backend/admincpmodules/apps.php');
?>
<h2>DSD+ Close Start Restart Statuslight</h2>
<h2>LogRecorder/TrunkingRecorder Close Start Restart Statuslight</h2>
<h2>Apache Close Restart Statuslight always green</h2>
<h2>Unitrunker Close Start Restart Statuslight</h2>
<h2>SDR# Close Start Restart Statuslight</h2>
<h2>Mumble/Murmur Close Start Restart Statuslight</h2>
<h2>Radiofeed Close Start Restart Statuslight</h2>