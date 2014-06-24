<?php
// If page is called without post variables, kill
if (!$_POST) {
	exit();
}
//Delete existing databases
	@unlink('../../database/calls.sqlite');
	@unlink('../../database/config.sqlite');
	@unlink('../../database/log.sqlite');
	@unlink('../../database/talkgroups.sqlite');
	@unlink('../../database/userdb.sqlite');
	@unlink('../../database/playlists.sqlite');
//make new databases
	@touch('../../database/calls.sqlite');
	@touch('../../database/config.sqlite');
	@touch('../../database/log.sqlite');
	@touch('../../database/talkgroups.sqlite');
	@touch('../../database/userdb.sqlite');
	@touch('../../database/playlists.sqlite');
// Define database classes
	$callsClass = new PDO('sqlite:../../database/calls.sqlite');
	$configClass = new PDO('sqlite:../../database/config.sqlite');
	$logClass = new PDO('sqlite:../../database/log.sqlite');
	$talkgroupsClass = new PDO('sqlite:../../database/talkgroups.sqlite');
	$userdbClass = new PDO('sqlite:../../database/userdb.sqlite');
	$playlistsClass = new PDO('sqlite:../../database/playlists.sqlite');
//unset extra vars
	unset($_POST['submit']);
#########################################################################
/////////////			CONFIGURE ADMIN USER 				/////////////
#########################################################################
$userdbClass->beginTransaction();// Start transaction
$username = strtolower($_POST['adminusername']);
$password = password_hash($_POST['adminpassword'], PASSWORD_BCRYPT); // hash password

$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // email authcode
for ($i = 0; $i < "32"; $i++) {
    @$authcode .= $characters[rand(0, strlen($characters) - 1)];
}
unset($statement);
$statement = "CREATE TABLE 'USERS' (UID INTEGER NOT NULL, USERNAME VARCHAR(32) NOT NULL, PWD VARCHAR NOT NULL, AUTHCODE VARCHAR(32) NOT NULL, 
			EMAIL VARCHAR(64) NOT NULL, LN VARCHAR(32) NOT NULL, FN VARCHAR(32) NOT NULL, ACCTENABLED INTEGER(1) DEFAULT 0 NOT NULL, 
			USRLVL INTEGER(1) DEFAULT 1 NOT NULL, NOTES TEXT(1000), PRIMARY KEY (UID), UNIQUE (USERNAME), UNIQUE (EMAIL))";
$userdbClass->query($statement); // run the query
unset($statement);
$statement = "INSERT INTO USERS (rowid, UID, USERNAME, PWD, AUTHCODE, EMAIL, LN, FN, ACCTENABLED, USRLVL, NOTES) VALUES 
			(1, 1, '{$username}', '{$password}', '{$authcode}', '{$_POST['globaladminemail']}', '{$_POST['ln']}', '{$_POST['fn']}', 1, 4, 'Administrator')";
$userdbClass->query($statement); // run the query
$userdbClass->commit();// commit transaction
unset($_POST['fn'],$_POST['ln'],$_POST['adminpassword'],$_POST['adminusername']);
unset($statement);
#########################################################################
/////////////		CONFIGURE CALLS DATABASE 				/////////////
#########################################################################
#Deprecated, LogRecorder will do this at first launch 
#########################################################################
/////////////		CONFIGURE TALKGROUPS DATABASE 			/////////////
#########################################################################
$talkgroupsClass->beginTransaction();// Start transaction
unset($statement);
$statement = "CREATE TABLE 'RIDRELATE' ('RID' INTEGER PRIMARY KEY NOT NULL,'NAME' VARCHAR,'COMMENT' VARCHAR)";
$talkgroupsClass->query($statement); // run the query
unset($statement);
$statement = "CREATE TABLE 'TAG' (ID INTEGER NOT NULL, 'TAG' VARCHAR NOT NULL, 'COLOR' VARCHAR, PRIMARY KEY (ID))";
$talkgroupsClass->query($statement); // run the query
unset($statement);
$statement = "CREATE TABLE 'TGRELATE' ('TGID' INTEGER PRIMARY KEY  NOT NULL UNIQUE , 'NAME' VARCHAR, 'COMMENT' VARCHAR, TAG INTEGER NOT NULL)";
$talkgroupsClass->query($statement); // run the query
	$tag = array(1 => array('NAME' => 'Multi-Dispatch', 'COLOR' =>'#e8e8e8'), 
				2 => array('NAME' => 'Law Dispatch', 'COLOR' =>'#aac2ff'), 
				3 => array('NAME' => 'Fire Dispatch', 'COLOR' =>'#ffaaaa'), 
				4 => array('NAME' => 'EMS Dispatch', 'COLOR' =>'#ffd8ac'), 
				6 => array('NAME' => 'Multi-Tac', 'COLOR' =>'#dbdbdb'), 
				7 => array('NAME' => 'Law Tac', 'COLOR' =>'#96aff0'), 
				8 => array('NAME' => 'Fire-Tac', 'COLOR' =>'#f09696'), 
				9 => array('NAME' => 'EMS-Tac', 'COLOR' =>'#f0c798'), 
				11 => array('NAME' => 'Interop', 'COLOR' =>'#b9b9b9'), 
				12 => array('NAME' => 'Hospital', 'COLOR' =>'#cf9dc5'), 
				13 => array('NAME' => 'Ham', 'COLOR' =>'#7f89a2'), 
				14 => array('NAME' => 'Public Works', 'COLOR' =>'#c8fbc4'), 
				15 => array('NAME' => 'Aircraft', 'COLOR' =>'#aa8ead'), 
				16 => array('NAME' => 'Federal', 'COLOR' =>'#7ea0a2'), 
				17 => array('NAME' => 'Business', 'COLOR' =>'#8da27e'), 
				20 => array('NAME' => 'Railroad', 'COLOR' =>'#a3927f'), 
				21 => array('NAME' => 'Other', 'COLOR' =>'#a8a8a8'), 
				22 => array('NAME' => 'Multi-Talk', 'COLOR' =>'#c7c7c7'), 
				23 => array('NAME' => 'Law Talk', 'COLOR' =>'#6d8dde'), 
				24 => array('NAME' => 'Fire-Talk', 'COLOR' =>'#de6d6d'), 
				25 => array('NAME' => 'EMS-Talk', 'COLOR' =>'#dea96d'), 
				26 => array('NAME' => 'Transportation', 'COLOR' =>'#a2a17f'), 
				29 => array('NAME' => 'Emergency Ops', 'COLOR' =>'#ce9dc9'), 
				30 => array('NAME' => 'Military', 'COLOR' =>'#ccce9d'), 
				31 => array('NAME' => 'Media', 'COLOR' =>'#b4f7f9'), 
				32 => array('NAME' => 'Schools', 'COLOR' =>'#f8f9b4'), 
				33 => array('NAME' => 'Security', 'COLOR' =>'#f9d1b4'), 
				34 => array('NAME' => 'Utilities', 'COLOR' =>'#b4f9eb'), 
				35 => array('NAME' => 'Data', 'COLOR' =>'#88d1c2'), 
				36 => array('NAME' => 'Deprecated', 'COLOR' =>'#cccccc'), 
				37 => array('NAME' => 'Corrections', 'COLOR' =>'#a0a0a0'), 
				100 => array('NAME' => 'Imported', 'COLOR' =>'#FFFFFF'));
	unset($statement);
	foreach ($tag as $ID => $nameAndColor) {
		$statement = "INSERT INTO TAG(ID, TAG, COLOR) VALUES ({$ID}, '{$nameAndColor['NAME']}', '{$nameAndColor['COLOR']}')";
		$talkgroupsClass->query($statement); // run the query
		unset($statement);
	}
$talkgroupsClass->commit();// commit transaction
#########################################################################
/////////////			CONFIGURE LOG DATABASE 				/////////////
#########################################################################
unset($statement);
$logClass->beginTransaction();// Start transaction
$time = time();
$statement = "CREATE TABLE 'LOG' ('TIMESTAMP' varchar(13) DEFAULT (null) ,'TYPE' varchar(5),'IP' varchar(15),'USER' varchar(16),'COMMENT' varchar(128))";
$logClass->query($statement); // run the query
$statement = "INSERT INTO LOG (TIMESTAMP, TYPE, IP, USER, COMMENT) VALUES ('{$time}','INSTL','LOCALHOST','ADMIN','SCANEYES WAS INSTALLED')";
$logClass->query($statement); // run the query
$logClass->commit();// commit transaction

#########################################################################
/////////////		CONFIGURE PLAYLIST DATABASE 			/////////////
#########################################################################
unset($statement);
$playlistsClass->beginTransaction();
$statement = "CREATE TABLE PLAYLIST (PID INTEGER NOT NULL, UID INTEGER, CALLS VARCHAR NOT NULL, COMMENT VARCHAR(300), PRIMARY KEY (PID))";
$playlistsClass->query($statement); // run the query
$playlistsClass->commit();// commit transaction
#########################################################################
///////////		CONFIGURE CONFIG DATABASE 					/////////////
#########################################################################
unset($statement);
$configClass->beginTransaction();// Start transaction
$statement = "CREATE TABLE 'SETTINGS' ('SETTING' VARCHAR NOT NULL , 'VALUE' VARCHAR);";
$configClass->query($statement); // make table
unset($statement);
foreach ($_POST as $setting => $value) {
	$statement = "INSERT INTO SETTINGS(SETTING, VALUE) VALUES ('{$setting}', '{$value}');";
	$configClass->query($statement); // insert into table
}
unset($statement);
$configClass->commit();// commit transaction
		
?>
<html>
	<head>
		<title>ScanEyes Install Step 4/5</title>
		<link rel="stylesheet" type="text/css" href="assets/style4.css">
	</head>
	<body>
		<form action="index.php?step=5" id="msform" method="POST" enctype="multipart/form-data">
			<ul id="progressbar">
					<li class="active">Import data from unitrunker</li>
			</ul>
			<fieldset>
				<h2 class="fs-title">ScanEyes Setup Page 4/5</h2>
				<h3 class="fs-subtitle">Unitrunker importing is optional.</h3>
				<video controls autoplay loop width="980px"><source src="assets/utexport.mp4" type="video/mp4"></video>
				<br>
					<center><b>Export XML from Unitrunker | Drag file into box | Wait 5 minutes</b></center>
				<br>
				<input type="file" name="xml" size="50" />
					<input class="action-button" type="submit" value="Next">
			</fieldset>
		</form>