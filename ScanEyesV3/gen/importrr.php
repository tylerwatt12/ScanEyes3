<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	echo "stopped";
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
include 'libraries/gen-gen.php';
include 'libraries/spec-radioreference.php';
/* 
This file imports from the RR DB into calls.db->TGRELATE
If TGs are found that aren't already in the SQLite DB, it will automatically add them
If TGs are found with identical TGID and descriptions, they will be skipped
If TGs are found with differing descriptions, the user will have the option to overwrite their existing definitions
RR premium account needed
*/
secReq(3); // Users 3+ can use this page
if (@!$config['rrdbsid']) {
	echo "rrdbsid is not set.";
}
if (@!$_POST['rrdbUsername'] && @!$_POST['rrdbPassword']) { // If there was
	echo '<b>Please log into Radioreference</b>
			<form method="POST">
				<input type="text" name="rrdbUsername">
				<input type="password" name="rrdbPassword">
				<input type="submit" value="login">
			</form>';
	exit();
}
$startTime = time(); // Start timer



$newTGID = rrAPIFetch($_POST['rrdbUsername'],$_POST['rrdbPassword']); // Get data from radioreference
if ($newTGID == false) { // If data get failed
	echo "bad username or password.";
	exit();
}
$curTGID = getTGList();

#$newRRTGIDS = array_diff($newTGID, $curTGID); #This line doesn't work with 2D arrays, below is the fix
foreach ($newTGID as $TGID => $data) { // Show only new TGIDs in RR DB
	if (@!$curTGID[$TGID]) {
		$newRRTGIDS[$TGID] = $data;
	}
}
 
#$deletedTGIDS = array_diff($curTGID, $newTGID); #This line doesn't work with 2D arrays, below is the fix
foreach ($curTGID as $TGID => $data) { // Show only deleted TGIDs from RR DB
	if (@!$newTGID[$TGID]) {
		$deletedTGIDS[$TGID] = $data;
	}
}
#$totaTGIDS = array_merge($curTGID, $newTGID); #Array_Merge renames the keys, below is the fix
foreach ($newTGID as $TGID => $data) { // Show every talkgroup in both
	if (@!$totalTGIDS[$TGID]) {
		$totalTGIDS[$TGID] = $data;
	}
}
foreach ($curTGID as $TGID => $data) {
	if (@!$totalTGIDS[$TGID]) {
		$totalTGIDS[$TGID] = $data;
	}
}
ksort($totalTGIDS);

foreach ($totalTGIDS as $TGID => $data) { // If talkgroup names or categories are different
	if (@$newTGID[$TGID]['NAME'] !== @$curTGID[$TGID]['NAME'] ||
		@$newTGID[$TGID]['CATEGORY'] !== @$curTGID[$TGID]['CATEGORY']) {
		$modifiedTGIDS[$TGID] = "rubbish"; // We're not using the Array values, just the keys.
	}
}
################################################
################################################
#		  PUSH UPDATED INFO TO USER 		   #
################################################
################################################
$tagIDs = getTGTags(); // Get list of TAGs (PoliceDispatch, Police-Tac, etc) AND SET COLORS FOR TGS from local DB
?>
<form action="page.php?updatetgid" method="POST">
	<div class="col-lg-12">
		<center><h1>Talkgroup batch update</h1></center>
		<b>Uncheck all/Check all</b>
		<table border=1>
			<thead>
				<tr>
					<th>Color</th>
					<th>Meaning</th>
				</tr>
			</thead>
			<tbody>
				<tr bgcolor="red">
					<td>Red</td>
					<td>Delete Talkgroup</td>
				</tr>
				<tr bgcolor="green">
					<td>Green</td>
					<td>Add Talkgroup</td>
				</tr>
				<tr bgcolor="blue">
					<td>Blue</td>
					<td>Update Talkgroup</td>
				</tr>
			</tbody>
		</table>
		<table border=1>
			<thead>
				<tr>
					<th>Modify</th>
					<th>ID</th>
					<th>Current Name</th>
					<th>Current Category</th>
					<th>New Name</th>
					<th>New Category</th>
				</tr>
			</thead>
			<tbody>
				
					<?php
					foreach ($totalTGIDS as $TGID => $rubbish) {
						// Don't use totalTGIDS as a datasource, only as a key reference source
						if (@$modifiedTGIDS[$TGID]) {$cellcolor = " bgcolor='blue' ";}
						if (@$deletedTGIDS[$TGID]) {$cellcolor = " bgcolor='red' ";}
						if (@$newRRTGIDS[$TGID]) {$cellcolor = " bgcolor='green' ";}
						if (@$cellcolor) {$checkbox = "checked";}else{$checkbox = "disabled";} // If new talkgroup, removed talkgroup, or updated talkgroup, enable check box, else disable checkbox
						echo "<tr>";
						echo "<td".@$cellcolor."><center><input type='checkbox' ".@$checkbox." ></center></td>";
						echo "<td>".@$TGID."</td>";
						echo "<td>".@$curTGID[$TGID]['NAME']."</td>";
						
						echo "<td bgcolor='".@$tagIDs[$curTGID[$TGID]['CATEGORY']]['COLOR']."'>".@$tagIDs[$curTGID[$TGID]['CATEGORY']]['TAG']."</td>";
						echo "<td>".@$newTGID[$TGID]['NAME']."</td>";
						echo "<td bgcolor='".@$tagIDs[$newTGID[$TGID]['CATEGORY']]['COLOR']."'>".@$tagIDs[$newTGID[$TGID]['CATEGORY']]['TAG']."</td>";
						echo "</tr>";
						unset($cellcolor);
					}
					?>
				
			</tbody>
		</table>
	</div>
	<div class="col-lg-6">
		<input type="submit" value="Update">
	</div>
</form>