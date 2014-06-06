<?php
	/*
	Tylerwatt12's logrecorder
	This program reads sdrsharptrunking.log and spawns a recording program if an active call is found
	It loop reads a file for changes, and if a file change is found, sox.exe is loaded
	Settings in this file include, location for sox, WaveAudioDevice (which recording device to use)
	Sample rate, location to sdrsharptrunking.log, your timezone, where calls should be saved,
	Which year to create folders up until (the init part of this script creates a folder for every day month and year until the date you specify
	Which email account to send alerts to, alerts are sent when log recorder is restarted, or if a call hasn't been received in an hour (configured in seconds)
	Also configurable is the SMTP server, username, password, and encryption method to use.
	This file also optionally hooks into the ScanEyes master log writer so there are config options for that too.
	*/
	#!!! Config settings are modified from the database !!!#
class configDB extends SQLite3{
    function __construct()
    {
        $this->open('../database/config.sqlite');
    }
}
class callsDB extends SQLite3{
    function __construct()
    {
        $this->open('../database/calls.sqlite');
    }
}
class logDB extends SQLite3{
    function __construct()
    {
        $this->open('../database/log.sqlite');
    }
}
$configHandle = new configDB();
date_default_timezone_set($configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='date_default_timezone_set'")->fetchArray()['VALUE']);
//Sox settings
$config['wad'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='wad'")->fetchArray()['VALUE'];
$config['srate'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='srate'")->fetchArray()['VALUE'];
//Program Settings
$config['trunkloc'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='trunkloc'")->fetchArray()['VALUE'];
$config['callsavedir'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='callsavedir'")->fetchArray()['VALUE'];
//Email settings
$config['globaladminemail'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='globaladminemail'")->fetchArray()['VALUE'];
$config['gmailaddr'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='gmailaddr'")->fetchArray()['VALUE'];
$config['gmailpass'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='gmailpass'")->fetchArray()['VALUE'];

require 'phpmailer/PHPMailerAutoload.php';

function sendMail($title,$body){
	global $config;
	$mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = "ssl"; // secure transfer enabled REQUIRED for GMail
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = $config['gmailaddr'];
	$mail->Password = $config['gmailpass'];
	$mail->SetFrom($config['gmailaddr']);
	$mail->WordWrap = 50; 
	$mail->isHTML(true);   
	$mail->Subject = $title;
	$mail->Body = $body;
	$mail->AddAddress($config['globaladminemail']);
	#$mail->SMTPDebug = 1;
	 if(!$mail->Send()){
		#return "Mailer Error: " . $mail->ErrorInfo;
	}else{
		#return "success";
	}
}

function readfil(){
	global $config;
	$file = file_get_contents($config['trunkloc']); //open log file
	list($devnull,$values) = explode("\n", $file); //discard first line of un-seful data
	list($return['action'],$return['receiver'],$return['freq'],$return['TGID'],$return['TGName'],$return['RID'],$return['RName']) = explode("\t", $values); //explode values into data for call recorder (TG and RID)
	if ($return['action'] == "Park") {
		$return['TGID'] = "Parked";
	}
	return $return;
}
function killsox(){
	#hacked no wait exec()
	#kills existing sox.exe
	@pclose(@popen("taskkill /F /IM sox.exe /T","r"));
}
function sanitizefs($filename){
		$invalidchars = array(">","<",":",'"',"/","\\","|","?","*");
		return str_replace($invalidchars,"",$filename);
}
#FUNCTIONS DONE

#INITIALIZE CODE
killsox(); //Kill any existing instances of SOX
echo "LOG RECORDER by Tylerwatt12. Version 4.0.3\n\n\n\n";
sendMail("LogRecorder STARTED","<b>LogRecorder was started or restarted on: ".date("F j, Y, g:i a")."</b>");
$logHandle = new logDB(); // Get ready to write to log
$timestamp = time();
$logHandle->busyTimeout(5000);
$logHandle->exec("INSERT INTO 'LOG' (TIMESTAMP,TYPE,IP,USER,COMMENT) VALUES ('{$timestamp}','LOGRC','127.0.0.1','LOCALHOST','LogRecorderv4 was restarted')"); // Take note of logrecorder's status
unset($logHandle);
sleep(1);



$staleFile = readfil();
$inc = 1;
while ($inc == 1) {
	#creating variable to compare to oldtargetid
	$currentFile = readfil();
	if ($currentFile['TGID'] != $staleFile['TGID']){
		#set old target ID for the next loop around
		$staleFile = $currentFile;
		#IF parked
		if ($currentFile['TGID'] == 'Parked'){
			killsox();
			echo "\nTalkgroup: ".sanitizefs($currentFile['TGID']);
			echo "\nName     : ".sanitizefs($currentFile['TGName']);
			echo "\nRadioID  : ".sanitizefs($currentFile['RID']);
			echo "\nRID name : ".sanitizefs($currentFile['RName']);
			echo "\n[WAITING]:[.........]";
		}elseif ($currentFile['action'] == 'Listen' && empty($currentFile['RID']) == false){
		#IF TG gets changed
			#kill previous sox instances
			killsox();
			echo "\nTalkgroup: ".sanitizefs($currentFile['TGID']);
			echo "\nName     : ".sanitizefs($currentFile['TGName']);
			echo "\nRadioID  : ".sanitizefs($currentFile['RID']);
			echo "\nRID name : ".sanitizefs($currentFile['RName']);
			echo "\n[.......]:[RECORDING]";

			#set folder format
			$dashedDate = date("Y-m-d");
			$date = date("Ymd");
			$fullSavePath = $config['callsavedir'].$dashedDate."/";
			$frozenTime = microtime();
			$saveFilename = substr($frozenTime, -10).substr($frozenTime, 2,6);
			if(!file_exists($fullSavePath)){ //if X:\calls\2014-05-20\ doesn't exist
				mkdir($fullSavePath); //make directory for date
			}
			pclose(popen("start /min sox.exe -t waveaudio ".$config['wad']." -r".$config['srate']." -c1 \"".$fullSavePath.$saveFilename.".mp3\"","r"));
			$callsHandle = new callsDB(); // call database
			$callsHandle->busyTimeout(5000);
			$callsHandle->exec("INSERT INTO \"".date('Y')."\" (UNIXTS,TGID,RID,LOCATION) VALUES ('{$saveFilename}','{$currentFile['TGID']}','{$currentFile['RID']}','{$date}')");
			unset($callsHandle);
			clearstatcache();	
		}
	}elseif((filemtime($config['trunkloc'])+3600) < time()){
		if (($sentTime+3600) < time()){
			sendMail("LogRecorder ERROR","<b>LogRecorder ERROR, no calls in the past hour on, date: ".date("F j, Y, g:i a")."</b>");
			$logHandle = new logDB(); // Get ready to write to log
			$timestamp = time();
			$logHandle->busyTimeout(5000);
			$logHandle->exec("INSERT INTO 'LOG' (TIMESTAMP,TYPE,IP,USER,COMMENT) VALUES ('{$timestamp}','LOGTM','127.0.0.1','LOCALHOST','LogRecorderV4 has not had any calls for an hour')"); // Take note of logrecorder's status
			unset($logHandle);
			$sentTime = time();
		}
		#if file hasn't been changed in an hour
	}
	#slows down poll 150ms, increase for slower CPUs
	usleep(150000);
}

















			



			
				





















?>