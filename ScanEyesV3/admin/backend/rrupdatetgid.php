<?php
include 'libraries/db-write.php';
include 'libraries/db-read.php';
include 'libraries/spec-radioreference.php';
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
/*
This is the processing page that updates the talkgroup info from importrr.php
*/
secReq(3); // Users 3+ can use this page
$timer = time(); // Start timer

$newTGIDS = rrAPIFetch($_POST['rrdbUsername'],$_POST['rrdbPassword']); //re-get info from Radioreference

$now = time();
echo "Fetched from RRAPI in ".($now-$timer)." seconds.<br>";

unset($_POST['rrdbUsername']); //remove username and password from POST array
unset($_POST['rrdbPassword']);
$statement = ""; // set variable blank to avoid notice error
foreach ($_POST as $TGID => $action) { //for every talkgroup that had a checkbox selected
	/* Possible actions
	m = modify talkgroup, use update
	r = remove talkgroup, use delete
	a = add talkgroup, use insert
	x = somehow a disabled checkbox slipped through, there is a bug in the code
	*/
	$TGID = numOnly($TGID); // clean talkgroup, numbers only
	$date = date('Y-m-d');
	#compile SQL statement
	if ($action == "r") {
		$statement .= "DELETE FROM TGRELATE WHERE TGID='{$TGID}'; ";
	}elseif ($action == "m") {
		$statement .= "UPDATE TGRELATE SET NAME='{$newTGIDS[$TGID]['NAME']}',COMMENT='Updated: {$date}',TAG='{$newTGIDS[$TGID]['CATEGORY']}' WHERE TGID='{$TGID}'; ";
	}elseif ($action == "a") {
		$statement .=   "INSERT INTO TGRELATE(TGID, NAME, COMMENT, TAG) VALUES ('{$TGID}', '{$newTGIDS[$TGID]['NAME']}', 'Added: {$date}', '{$newTGIDS[$TGID]['CATEGORY']}'); ";
	}
}
$now = time();
echo "Compiled SQL statement in ".($now-$timer)." seconds.<br>";

echo runSQLtalkgroupsDB($statement);

$now = time();
echo "Executed statement in ".($now-$timer)." seconds.<br>";
#run statement
#include HTML output
?>