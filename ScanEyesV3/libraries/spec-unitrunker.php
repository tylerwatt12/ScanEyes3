<?php
function parseUTXML($filename,$type){
	global $config;
################################################
################################################
#				GET DATA FROM XML 			   #
################################################
################################################
	$xml = simplexml_load_file($filename);
	if ($type == "talkgroups") { // If user wants to get talktroups
		foreach ($xml->System->Group as $value) {
			if (empty($value['label']) == false) {
				 $label = (string)$value['label'];
				$newTGID[(string)$value['id']] = array( "NAME" => $label,"CATEGORY" => 100);
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

?>