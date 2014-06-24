<?php
include 'libraries/db-write.php';
include 'libraries/db-read.php';
include 'libraries/spec-unitrunker.php';
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}elseif (@!$_POST['xmlfile']) {
	exit();
}
/*
This is the processing page that updates the talkgroup info from importrr.php
*/
secReq(3); // Users 3+ can use this page
$timer = time(); // Start timer

$newRIDS = parseUTXML($_POST['xmlfile'],"radioids"); //re-get info from Radioreference

unset($_POST['xmlfile']); //remove file from POST array
$statement = ""; // set variable blank to avoid notice error
$deletecounter = 0;//set counters
$updatecounter = 0;
$addcounter = 0;

$date = date('Y-m-d');
$talkgroupsClass->beginTransaction();// Start transaction
foreach ($_POST as $RID => $action) { //for every talkgroup that had a checkbox selected
	/* Possible actions
	m = modify talkgroup, use update
	r = remove talkgroup, use delete
	a = add talkgroup, use insert
	x = somehow a disabled checkbox slipped through, there is a bug in the code
	*/
	$RID = numOnly($RID); // clean talkgroup, numbers only
	#compile SQL statement
	if ($action == "r") {
		$statement = "DELETE FROM RIDRELATE WHERE RID='{$RID}'; ";
		$deletecounter++;
	}elseif ($action == "m") {
		$statement = "UPDATE RIDRELATE SET NAME='{$newRIDS[$RID]['NAME']}',COMMENT='Updated: {$date}' WHERE RID='{$RID}'; ";
		$updatecounter++;
	}elseif ($action == "a") {
		$statement = "INSERT INTO RIDRELATE(RID, NAME, COMMENT) VALUES ('{$RID}', '{$newRIDS[$RID]['NAME']}', 'Added: {$date}'); ";
		$addcounter++;
	}
	$talkgroupsClass->query($statement); // run the query
}
$talkgroupsClass->commit();// commit transaction
$now = time();
unlink('static/unitrunker.xml');
growl("notice","Added: ".$addcounter." RIDs, Updated:".$updatecounter." RIDs, Deleted: ".$deletecounter." RIDs");
growl("notice","Deleted temp file");
?>