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
This is the processing page that updates the talkgroup info from importuttgid.php
*/
secReq(3); // Users 3+ can use this page
$timer = time(); // Start timer

$newTGIDS = parseUTXML($_POST['xmlfile'],"talkgroups"); //re-get info from file
foreach ($newTGIDS as $TGID => $valueArray) { // Guess gategory IDs from keywords in talkgroup name
	$newTGIDS[$TGID]["CATEGORY"] = autoTagCategories($valueArray["NAME"]);
}
$now = time();
growl("notice","Fetched from RRAPI in ".($now-$timer)."Fetched from XML in ".($now-$timer)." seconds.");

unset($_POST['xmlfile']); //remove file from POST array
$statement = ""; // set variable blank to avoid notice error
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
unlink('static/unitrunker.xml');
growl("notice","Added: ".$addcounter." talkgroups, Updated:".$updatecounter." talkgroups, Deleted: ".$deletecounter." talkgroups");
growl("notice","Deleted temp file");
?>