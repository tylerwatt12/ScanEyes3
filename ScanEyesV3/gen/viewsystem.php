<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
include 'libraries/gen-gen.php';
/* 
This file imports from the RR DB into calls.db->TGRELATE
If TGs are found that aren't already in the SQLite DB, it will automatically add them
If TGs are found with identical TGID and descriptions, they will be skipped
If TGs are found with differing descriptions, the user will have the option to overwrite their existing definitions
RR premium account needed
*/
secReq($config['mintgidbrowselvl']); // Users 1+ can use this page






$db = new callsDB(); // Call database instance
$db->busyTimeout(5000);
$result = $db->query("SELECT * FROM TGRELATE"); // Select all the TGIDs from the DB
unset($db); // unlock database
while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
 if(!isset($res['TGID'])) continue;  //If there are no more values, kill loop
  $localdb[$res['TGID']] = str_replace("\n", '',$res['NAME']); //Set array, $localdb, key TGID, value TGNAME
}
///////					STOPPED hebrev(hebrew_text)															/////////////////////////////////////////
$compiledStatement = "";// set SQL placeholder
$today = date('Y-m-d'); // for 3rd column of TGRELATE table
foreach ($talkGroup as $TGID => $csvdbName) { // for every local TGID
	If (array_key_exists($TGID, $localdb) && $csvdbName == $localdb[$TGID]){ //If the TGID exists in current local DB and TGNames are identical
		$skippedIdenticals[$TGID] = $csvdbName;
	}elseif(array_key_exists($TGID, $localdb) && $csvdbName !== $localdb[$TGID]){ //If the TGID exists in local, but TGNames are different
		//Warn user to overwrite, compile into overwrite array.
		if (@$_GET['overwriteall'] = 1) {//If user said overwrite was okay
			$compiledStatement.= "UPDATE TGRELATE SET NAME='{$csvdbName}',COMMENT='updated: {$today}' WHERE TGID='{$TGID}'; ";
			$added[$TGID] = $csvdbName;
		}else{
			$overwriteTo[$TGID] = $csvdbName; // New TG name the script wants to write
			$overwriteFrom[$TGID] = $localdb[$TGID]; // Current local database TG
		}		
	}else{
		$compiledStatement.= "INSERT INTO TGRELATE (TGID,NAME,COMMENT) VALUES ('{$TGID}','{$csvdbName}','added: {$today}'); ";
		// Insert value into TGRELATE
		$added[$TGID] = $csvdbName;
	}
}
//Report generation
/*
RETURNS WITH
--overwrite(not a variable)-- = talkgroups with conflicts
$overwriteTo = what the script wants to replace them with
$overwriteFrom = what the talkgroup values are currently before overwriting

$added = talkgroups added that are not in the database

$skippedIdenticals = talkgroups skipped because they have the same value as before
*/
?>
<h1>Added/confirmed updated talkgroups</h1>
<table border=1>
	<thead>
		<tr>
			<th>TGID</th>
			<th>Name</th>
			<th>Tag</th>
			<th>Color</th>
	</thead>
	<tbody>
		<?php
			if (@$added) {
				foreach ($added as $TGID => $name) {
					echo "<tr><td>".$TGID."</td><td>".$name."</td><td>".$talkTag[$TGID]."</td><td bgcolor=\"".$talkColor[$TGID]."\"></td></tr>";
				}
			}
		?>
	</tbody>
</table>
<h1>Talkgroups that were not overwritten</h1><a href="?page=importcsv&overwriteall=1">Overwrite All</a>
<table border=1>
	<thead>
		<tr>
			<th>TGID</th>
			<th>From</th>
			<th>To</th>
			<th>Tag</th>
			<th>Color</th>
	</thead>
	<tbody>
		<?php
			if (@$overwriteFrom) {
				foreach ($overwriteFrom as $TGID => $name) {
					echo "<tr><td>".$TGID."</td><td>".$name."</td><td>".$overwriteTo[$TGID]."</td><td>".$talkTag[$TGID]."</td><td bgcolor=\"".$talkColor[$TGID]."\"></td></tr>";
				}
			}
		?>
	</tbody>
</table>
<h1>Skipped talkgroups</h1>
<table border=1>
	<thead>
		<tr>
			<th>TGID</th>
			<th>Name</th>
			<th>Tag</th>
			<th>Color</th>
	</thead>
	<tbody>
		<?php
			if (@$skippedIdenticals) {
				foreach ($skippedIdenticals as $TGID => $name) {
					echo "<tr><td>".$TGID."</td><td>".$name."</td><td>".$talkTag[$TGID]."</td><td bgcolor=\"".$talkColor[$TGID]."\"></td></tr>";
				}
			}
		?>
	</tbody>
</table>



?>