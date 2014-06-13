<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
include 'libraries/spec-unitrunker.php';
/* 
This file imports from an exported unitrunker xml into talkgroups.db->TGRELATE
If TGs are found that aren't already in the SQLite DB, it will automatically add them
If TGs are found with identical RID and descriptions, they will be skipped
If TGs are found with differing descriptions, the user will have the option to overwrite their existing definitions
RR premium account needed
*/
secReq(3); // Users 3+ can use this page
if (@!$_FILES['xml']) { // If there was
	include('admin/UI/UTupload.php'); // include login form
	exit();
}
$startTime = time(); // Start timer
move_uploaded_file($_FILES['xml']["tmp_name"],"static/unitrunker.xml");
$filename = "static/unitrunker.xml";
$newRID = parseUTXML($filename,"radioids"); // Get data from XML file
$curRID = getRList(); // Get data from local DB
#$newRRRIDS = array_diff($newRID, $curRID); #This line doesn't work with 2D arrays, below is the fix
	if (@$newRID) {
		foreach ($newRID as $RID => $data) { // Show only new RIDs in RR DB
			if (@!$curRID[$RID]) {
				$newRRRIDS[$RID] = $data;
			}
		}
	}
///
#$deletedRIDS = array_diff($curRID, $newRID); #This line doesn't work with 2D arrays, below is the fix
	if (@$curRID) {
		foreach ($curRID as $RID => $data) { // Show only deleted RIDs from RR DB
			if (@!$newRID[$RID]) {
				$deletedRIDS[$RID] = $data; 
			}
		}
	}
///
#$totaRIDS = array_merge($curRID, $newRID); #Array_Merge renames the keys, below is the fix
	if (@$newRID) {
		foreach ($newRID as $RID => $data) { // Show every talkgroup in both
			if (@!$totalRIDS[$RID]) {
				$totalRIDS[$RID] = $data; // make array of every talkgroup old and new
			}
		}
		if (@$curRID) {
			foreach ($curRID as $RID => $data) { // Show every talkgroup in both
				if (@!$totalRIDS[$RID]) {
					$totalRIDS[$RID] = $data; // make array of every talkgroup old and new
				}
			}
		}
		ksort($totalRIDS);
	}
///

foreach ($totalRIDS as $RID => $data) { // If talkgroup names or categories are different
	if (@$newRID[$RID]['NAME'] !== @$curRID[$RID]['NAME'] || // make array of only modified talkgroups
		@$newRID[$RID]['CATEGORY'] !== @$curRID[$RID]['CATEGORY']) {
		$modifiedRIDS[$RID] = "rubbish"; // We're not using the Array values, just the keys.
	}
}
################################################
################################################
#		  PUSH UPDATED INFO TO USER 		   #
################################################
################################################
include('admin/UI/utRIDupdateTable.php'); // get output table
?>

