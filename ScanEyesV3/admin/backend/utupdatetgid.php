<?php
include 'libraries/db-write.php';
include 'libraries/db-read.php';
include 'libraries/spec-unitrunker.php';
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
/*
This is the processing page that updates the talkgroup info from importrr.php
*/
secReq(3); // Users 3+ can use this page
$timer = time(); // Start timer

$newTGIDS = parseUTXML($_POST['xmlfile'],"talkgroups"); //re-get info from Radioreference

$now = time();
echo "Fetched from XML in ".($now-$timer)." seconds.<br>";

unset($_POST['xmlfile']); //remove file from POST array
$statement = ""; // set variable blank to avoid notice error
$deletecounter = 0;//set counters
$updatecounter = 0;
$addcounter = 0;

$date = date('Y-m-d');

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
		$statement .= "DELETE FROM TGRELATE WHERE TGID='{$TGID}'; ";
		$deletecounter++;
	}elseif ($action == "m") {
		$statement .= "UPDATE TGRELATE SET NAME='{$newTGIDS[$TGID]['NAME']}',COMMENT='Updated: {$date}',TAG='{$newTGIDS[$TGID]['CATEGORY']}' WHERE TGID='{$TGID}'; ";
		$updatecounter++;
	}elseif ($action == "a") {
		$statement .=   "INSERT INTO TGRELATE(TGID, NAME, COMMENT, TAG) VALUES ('{$TGID}', '{$newTGIDS[$TGID]['NAME']}', 'Added: {$date}', '{$newTGIDS[$TGID]['CATEGORY']}'); ";
		$addcounter++;
	}
}
$now = time();
echo "Compiled SQL statement in ".($now-$timer)." seconds.<br>";

echo runSQLtalkgroupsDB($statement)."<br>";

$now = time();
echo "Executed statement in ".($now-$timer)." seconds.<br>";
unlink('static/unitrunker.xml');
echo "Added: ".$addcounter." talkgroups, Updated:".$updatecounter." talkgroups, Deleted: ".$deletecounter." talkgroups<br>";
echo "Deleted temp file";
?>