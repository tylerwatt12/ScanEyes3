<?php
	set_time_limit(120);
	function parseUTXML($filename,$type){
		################################################
		################################################
		#				GET DATA FROM XML 			   #
		################################################
		################################################
		global $config;
		$xml = simplexml_load_file($filename);
		if ($type == "talkgroups") { // If user wants to get talktroups
			foreach ($xml->System->Group as $value) {
				if (empty($value['label']) == false) {
					$newTGID[(string)$value['id']] = array( "NAME" => $value['label'],"CATEGORY" => '100');
				}
			}
		}

		if ($type == "radioids") { // If user wants to get RIDs
			foreach ($xml->System->User as $value) {
				if (empty($value['label']) == false) {
					$newTGID[(string)$value['id']] = $value['label'];
				}
			}
		}
		ksort($newTGID);
		return $newTGID;
	}
	function runSQLtalkgroupsDB($statement){
		################################################
		################################################
		#				WRITE SQL TO DB 			   #
		################################################
		################################################
		$db = new talkgroupsDB(); // Call database instance
		$db->busyTimeout(5000);
		$result = $db->exec($statement);
		if (@$db->lastErrorMsg() == "not an error") {
			return "XML Import succeeded";
		}else{
			return "There was an error: ".$db->lastErrorMsg()."<br>";
		}
	}
	class talkgroupsDB extends SQLite3{
		    function __construct()
		    {
		        $this->open('../../database/talkgroups.sqlite'); //Done
		    }
		}
#########################################################################
/////////////			IMPORT TALKGROUPS DATABASE 			/////////////
#########################################################################
if (@$_FILES['xml']) {
	$talkgroups = parseUTXML($_FILES['xml']["tmp_name"],"talkgroups"); //parse xml
	$radioIDS = parseUTXML($_FILES['xml']["tmp_name"],"radioids"); //parse xml

	$date = date('Y-m-d');
	$statement = "";

	foreach ($talkgroups as $TGID => $array) {
		$statement .=   "INSERT INTO TGRELATE(TGID, NAME, COMMENT, TAG) VALUES ('{$TGID}', '{$array['NAME']}', 'Added: {$date}', '{$array['CATEGORY']}'); ";
	}
	foreach ($radioIDS as $RID => $name) {
		$statement .=   "INSERT INTO RIDRELATE(RID, NAME, COMMENT) VALUES ('{$RID}', '{$name}', 'Added: {$date}'); ";
	}
	$result = runSQLtalkgroupsDB($statement); // Execute import
	unlink($_FILES['xml']["tmp_name"]); // Delete temp file
	echo $result;
}
?>
<br><b>You may now proceed to <a href="../index.php">log in</a></b>