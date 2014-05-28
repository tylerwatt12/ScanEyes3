<?php
$configHandle = new configDB();
	//All config options are handled through the database.
	date_default_timezone_set($configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='date_default_timezone_set'")->fetchArray()['VALUE']);
	$config['uacode'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='uacode'")->fetchArray()['VALUE'];
	$config['gaenabled'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='gaenabled'")->fetchArray()['VALUE'];
	$config['shareenabled'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='shareenabled'")->fetchArray()['VALUE'];
	$config['acctcreateenabled'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='acctcreateenabled'")->fetchArray()['VALUE'];
	$config['gueststream'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='gueststream'")->fetchArray()['VALUE'];
	$config['rrdbsid'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='rrdbsid'")->fetchArray()['VALUE'];
	$config['domain'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='domain'")->fetchArray()['VALUE'];
	$config['dsdoptions'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='dsdoptions'")->fetchArray()['VALUE'];
	$config['sndext'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='sndext'")->fetchArray()['VALUE'];
	$config['motdenabled'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='motdenabled'")->fetchArray()['VALUE'];
	$config['motdtitle'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='motdtitle'")->fetchArray()['VALUE'];
	$config['motdbody'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='motdbody'")->fetchArray()['VALUE'];
	$config['httpmethod'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='httpmethod'")->fetchArray()['VALUE'];

	$config['gmailaddr'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='gmailaddr'")->fetchArray()['VALUE'];
	$config['gmailpass'] = $configHandle->query("SELECT * FROM SETTINGS WHERE SETTING='gmailpass'")->fetchArray()['VALUE'];


?>