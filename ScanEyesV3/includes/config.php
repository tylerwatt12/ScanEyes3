<?php
	$db = new configDB(); // Call database instance
	$db->busyTimeout(5000);
	$result = $db->query("SELECT VALUE,SETTING FROM SETTINGS"); // Select all the TGIDs from the DB
	unset($db); // unlock database
	while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
		if(!isset($res['SETTING'])) continue;  //If there are no more values, kill loop
		$config[$res['SETTING']] = $res['VALUE'];
	}
	date_default_timezone_set($config["date_default_timezone_set"]);
?>