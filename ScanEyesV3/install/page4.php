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
//Create databases
	touch('../../database/calls.sqlite');
	touch('../../database/config.sqlite');
	touch('../../database/log.sqlite');
	touch('../../database/talkgroups.sqlite');
	touch('../../database/userdb.sqlite');
	touch('../../database/playlists.sqlite');
// Define database classes
	class callsDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../../database/calls.sqlite'); //Done
	    }
	}
	class configDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../../database/config.sqlite'); //Done
	    }
	}
	class logDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../../database/log.sqlite'); //Done
	    }
	}
	class talkgroupsDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../../database/talkgroups.sqlite'); //Done
	    }
	}
	class userDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../../database/userdb.sqlite'); //Done
	    }
	}
	class playlistDB extends SQLite3{
	    function __construct()
	    {
	        $this->open('../../database/playlists.sqlite'); //Done
	    }
	}
#########################################################################
///////////		CONFIGURE CONFIG DATABASE 					/////////////
#########################################################################
$db = new configDB(); // Call database instance
$db->busyTimeout(5000); // Fill config info
$db->exec("CREATE TABLE 'SETTINGS' ('SETTING' VARCHAR NOT NULL , 'VALUE' VARCHAR NOT NULL , 'COMMENT' VARCHAR);
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('date_default_timezone_set', '{$_POST['date_default_timezone_set']}', 'Timezone of server');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('uacode', '{$_POST['uacode']}', 'Google Analytics code');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('gaenabled', '{$_POST['gaenabled']}', 'Is Google Analytics enabled [yes/no]');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('shareenabled', '{$_POST['shareenabled']}', 'Is sharing enabled [yes/no]');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('acctcreateenabled', '{$_POST['acctcreateenabled']}', 'Users allowed to create accounts [yes/no]');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('gueststream', '{$_POST['gueststream']}', 'Can guests stream [yes/no]');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('minguestpllvl', '{$_POST['minguestpllvl']}', 'Minimum user level to make playlists [1-4]');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('maxcpp', '{$_POST['maxcpp']}', 'Maximum calls per playlist [1-1024]');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('rrdbsid', '{$_POST['rrdbsid']}', 'RadioReference Database SID');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('domain', '{$_POST['domain']}', 'servers FQDN');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('dsdoptions', '{$_POST['dsdoptions']}', 'Options for P25 decoding');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('sndext', '{$_POST['sndext']}', 'file format for recordings');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('motdenabled', '{$_POST['motdenabled']}', 'Message of day enabled [yes/no]');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('motdtitle', '{$_POST['motdtitle']}', 'Title for MOTD');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('motdbody', '{$_POST['motdbody']}', 'message for MOTD');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('httpmethod', '{$_POST['httpmethod']}', 'Access method for website [http:// / https://]');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('gmailaddr', '{$_POST['gmailaddr']}', 'Gmail address used to send emails');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('gmailpass', '{$_POST['gmailpass']}', 'Gmail password used to send emails');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('wad', '{$_POST['wad']}', 'Windows audio device for LogRecorder');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('srate', '{$_POST['srate']}', 'Sample rate for LogRecorder');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('trunkloc', '{$_POST['trunkloc']}', 'Full path to sdrsharptrunking.log, with forward slashes');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('globaladminemail', '{$_POST['globaladminemail']}', 'Email for sending error alerts to');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('callsavedir', '{$_POST['callsavedir']}', 'Call save directory, relative to recorder.php, use forwardslashes, and trailing fws');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('rrapikey', '{$_POST['rrapikey']}', 'Key provided by radioreference allowing premium users to add talkgroup info automagically');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('mintgidbrowselvl', '{$_POST['mintgidbrowselvl']}', 'Minimum required user lvl to browse TGs and RID assignments [1-4], 1 is default');
			INSERT INTO SETTINGS(SETTING, VALUE, COMMENT) VALUES ('mincallbrowselvl', '{$_POST['mincallbrowselvl']}', 'Minimum required user lvl to search and browse call database [1-4], 1 is default');");
unset($db);
#########################################################################
/////////////			CONFIGURE ADMIN USER 				/////////////
#########################################################################
$username = strtolower($_POST['adminusername']);
$password = password_hash($_POST['adminpassword'], PASSWORD_BCRYPT);	// hash password

$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; // email authcode
for ($i = 0; $i < "32"; $i++) {
    @$authcode .= $characters[rand(0, strlen($characters) - 1)];
}

$db = new userDB(); // Call database instance
$db->busyTimeout(5000); // Create admin user
$db->exec("CREATE TABLE 'USERS' (UID INTEGER NOT NULL, USERNAME VARCHAR(32) NOT NULL, PWD VARCHAR NOT NULL, AUTHCODE VARCHAR(32) NOT NULL, 
			EMAIL VARCHAR(64) NOT NULL, LN VARCHAR(32) NOT NULL, FN VARCHAR(32) NOT NULL, ACCTENABLED INTEGER(1) DEFAULT 0 NOT NULL, 
			USRLVL INTEGER(1) DEFAULT 1 NOT NULL, NOTES TEXT(1000), PRIMARY KEY (UID), UNIQUE (USERNAME), UNIQUE (EMAIL));
			INSERT INTO USERS (rowid, UID, USERNAME, PWD, AUTHCODE, EMAIL, LN, FN, ACCTENABLED, USRLVL, NOTES) VALUES 
			(1, 1, '{$username}', '{$password}', '{$authcode}', '{$_POST['globaladminemail']}', '{$_POST['ln']}', '{$_POST['fn']}', 1, 4, '');");
unset($db);
#########################################################################
/////////////		CONFIGURE CALLS DATABASE 				/////////////
#########################################################################
$statement = "";
$db = new callsDB(); // Call database instance
$db->busyTimeout(5000); // Create 5 years of call database tables
for ($year=date("Y"); $year < (date("Y")+5); $year++) { 
	$statement .= "CREATE TABLE '{$year}' ('CALLID' INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL  UNIQUE , 'UNIXTS' INTEGER NOT NULL  UNIQUE , 
			'TGID' INTEGER NOT NULL , 'RID' INTEGER NOT NULL , LOCATION VARCHAR(10) , COMMENT VARCHAR(512)); ";
}
$db->exec($statement);
unset($db);
#########################################################################
/////////////		CONFIGURE TALKGROUPS DATABASE 			/////////////
#########################################################################
$db = new talkgroupsDB(); // Call database instance
$db->busyTimeout(5000); // Create tables for RIDs, TGIDs, and TAGs
$db->exec("CREATE TABLE 'RIDRELATE' ('RID' INTEGER PRIMARY KEY NOT NULL,'NAME' VARCHAR,'COMMENT' VARCHAR);
		CREATE TABLE 'TAG' (ID INTEGER NOT NULL, 'TAG' VARCHAR NOT NULL, 'COLOR' VARCHAR, PRIMARY KEY (ID));
		CREATE TABLE 'TGRELATE' ('TGID' INTEGER PRIMARY KEY  NOT NULL UNIQUE , 'NAME' VARCHAR, 'COMMENT' VARCHAR, TAG INTEGER NOT NULL);
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (1, 'Multi-Dispatch', '#e8e8e8');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (2, 'Law Dispatch', '#aac2ff');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (3, 'Fire Dispatch', '#ffaaaa');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (4, 'EMS Dispatch', '#ffd8ac');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (6, 'Multi-Tac', '#dbdbdb');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (7, 'Law Tac', '#96aff0');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (8, 'Fire-Tac', '#f09696');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (9, 'EMS-Tac', '#f0c798');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (11, 'Interop', '#b9b9b9');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (12, 'Hospital', '#cf9dc5');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (13, 'Ham', '#7f89a2');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (14, 'Public Works', '#c8fbc4');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (15, 'Aircraft', '#aa8ead');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (16, 'Federal', '#7ea0a2');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (17, 'Business', '#8da27e');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (20, 'Railroad', '#a3927f');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (21, 'Other', '#a8a8a8');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (22, 'Multi-Talk', '#c7c7c7');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (23, 'Law Talk', '#6d8dde');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (24, 'Fire-Talk', '#de6d6d');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (25, 'EMS-Talk', '#dea96d');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (26, 'Transportation', '#a2a17f');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (29, 'Emergency Ops', '#ce9dc9');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (30, 'Military', '#ccce9d');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (31, 'Media', '#b4f7f9');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (32, 'Schools', '#f8f9b4');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (33, 'Security', '#f9d1b4');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (34, 'Utilities', '#b4f9eb');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (35, 'Data', '#88d1c2');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (36, 'Deprecated', '#cccccc');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (37, 'Corrections', '#a0a0a0');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (100, 'Imported', '#FFFFFF'); ");
unset($db);
#########################################################################
/////////////			CONFIGURE LOG DATABASE 				/////////////
#########################################################################
$time = time();
$db = new logDB(); // Call database instance
$db->busyTimeout(5000); // Create tables for event log
$db->exec("CREATE TABLE 'LOG' ('TIMESTAMP' varchar(13) DEFAULT (null) ,'TYPE' varchar(5),'IP' varchar(15),'USER' varchar(16),'COMMENT' varchar(128));
			INSERT INTO LOG (TIMESTAMP, TYPE, IP, USER, COMMENT) VALUES ('{$time}','INSTL','LOCALHOST','ADMIN','SCANEYES WAS INSTALLED')");
unset($db);
#########################################################################
/////////////		CONFIGURE PLAYLIST DATABASE 			/////////////
#########################################################################
$db = new playlistDB(); // Call database instance
$db->busyTimeout(5000); // Create tables for playlist log
$db->exec("CREATE TABLE PLAYLIST (PID INTEGER NOT NULL, UID INTEGER, CALLS VARCHAR NOT NULL, COMMENT VARCHAR(300), PRIMARY KEY (PID))");
unset($db);

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