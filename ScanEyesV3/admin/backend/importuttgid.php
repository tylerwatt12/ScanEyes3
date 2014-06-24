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
If TGs are found with identical TGID and descriptions, they will be skipped
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
$newTGID = parseUTXML($filename,"talkgroups"); // Get data from XML file
foreach ($newTGID as $TGID => $valueArray) { // Guess gategory IDs from keywords in talkgroup name
	$newTGID[$TGID]["CATEGORY"] = autoTagCategories($valueArray["NAME"]);
}
$curTGID = getTGList(); // Get data from local DB
#$newRRTGIDS = array_diff($newTGID, $curTGID); #This line doesn't work with 2D arrays, below is the fix
	if (@$newTGID) {
		foreach ($newTGID as $TGID => $data) { // Show only new TGIDs in RR DB
			if (@!$curTGID[$TGID]) {
				$newRRTGIDS[$TGID] = $data;
			}
		}
	}
///
#$deletedTGIDS = array_diff($curTGID, $newTGID); #This line doesn't work with 2D arrays, below is the fix
	if (@$curTGID) {
		foreach ($curTGID as $TGID => $data) { // Show only deleted TGIDs from RR DB
			if (@!$newTGID[$TGID]) {
				$deletedTGIDS[$TGID] = $data; 
			}
		}
	}
///
#$totaTGIDS = array_merge($curTGID, $newTGID); #Array_Merge renames the keys, below is the fix
	if (@$newTGID) {
		foreach ($newTGID as $TGID => $data) { // Show every talkgroup in both
			if (@!$totalTGIDS[$TGID]) {
				$totalTGIDS[$TGID] = $data; // make array of every talkgroup old and new
			}
		}
		if (@$curTGID) {
			foreach ($curTGID as $TGID => $data) { // Show every talkgroup in both
				if (@!$totalTGIDS[$TGID]) {
					$totalTGIDS[$TGID] = $data; // make array of every talkgroup old and new
				}
			}
		}
		ksort($totalTGIDS);
	}
///

foreach ($totalTGIDS as $TGID => $data) { // If talkgroup names or categories are different
	if (@$newTGID[$TGID]['NAME'] !== @$curTGID[$TGID]['NAME'] || // make array of only modified talkgroups
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
include('admin/UI/utTGIDupdateTable.php'); // get output table
?>

