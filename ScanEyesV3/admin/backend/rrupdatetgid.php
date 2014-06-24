<?php
include 'libraries/db-write.php';
include 'libraries/db-read.php';
include 'libraries/spec-radioreference.php';
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}elseif (@!$_POST['rrdbUsername'] || @!$_POST['rrdbPassword']) {
	exit();
}
/*
This is the processing page that updates the talkgroup info from importrr.php
*/
secReq(3); // Users 3+ can use this page
$timer = time(); // Start timer

$newTGIDS = rrAPIFetch($_POST['rrdbUsername'],$_POST['rrdbPassword']); //re-get info from Radioreference

$now = time();
growl("notice","Fetched from RRAPI in ".($now-$timer)." seconds.");

unset($_POST['rrdbUsername']); //remove username and password from POST array
unset($_POST['rrdbPassword']);

$deletecounter = 0;//set counters
$updatecounter = 0;
$addcounter = 0;

$date = date('Y-m-d');
$talkgroupsClass->beginTransaction();// Start transaction
foreach ($_POST as $TGID => $action) { //for every talkgroup that had a checkbox selected
	/* Possible actions
	m = modify talkgroup, use update
	r = remove talkgroup, use delete
	a = add talkgroup, use insert
	x = somehow a disabled checkbox slipped through, there is a bug in the code
	*/
	$TGID = numOnly($TGID); // clean talkgroup, numbers only
	#compile SQL statement
	if ($action == "r") {
		$statement = "DELETE FROM TGRELATE WHERE TGID='{$TGID}'; ";
		$deletecounter++;
	}elseif ($action == "m") {
		$statement = "UPDATE TGRELATE SET NAME='{$newTGIDS[$TGID]['NAME']}',COMMENT='Updated: {$date}',TAG='{$newTGIDS[$TGID]['CATEGORY']}' WHERE TGID='{$TGID}'; ";
		$updatecounter++;
	}elseif ($action == "a") {
		$statement =   "INSERT INTO TGRELATE(TGID, NAME, COMMENT, TAG) VALUES ('{$TGID}', '{$newTGIDS[$TGID]['NAME']}', 'Added: {$date}', '{$newTGIDS[$TGID]['CATEGORY']}'); ";
		$addcounter++;
	}
	$talkgroupsClass->query($statement); // run the query
}
$talkgroupsClass->commit();// commit transaction
growl("notice","Added: ".$addcounter." talkgroups, Updated:".$updatecounter." talkgroups, Deleted: ".$deletecounter." talkgroups");
?>