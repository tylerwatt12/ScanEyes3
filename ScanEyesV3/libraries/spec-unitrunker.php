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
				$label = (string)$value['label'];
				$newTGID[(string)$value['id']] = array( "NAME" => $label);
			}
		}
	}
	ksort($newTGID);
	return $newTGID;
}
function autoTagCategories($TGNAME){
	// This dumb piece of code guesses the proper category for unitrunker imports
	if (stripos($TGNAME, "multi") !== false && stripos($TGNAME, "dispatch") !== false ) {return 1;
	}elseif (stripos($TGNAME, "dispatch") !== false && stripos($TGNAME, "center") !== false ) {return 1;
	}elseif (stripos($TGNAME, "tac") !== false && stripos($TGNAME, "multi") !== false ) {return 6;

	}elseif (stripos($TGNAME, "tac") !== false && stripos($TGNAME, "pd") !== false ) {return 7;
	}elseif (stripos($TGNAME, "ptac") !== false) {return 7;
	}elseif (stripos($TGNAME, "tac") !== false && stripos($TGNAME, "police") !== false ) {return 7;

	}elseif (stripos($TGNAME, "tac") !== false && stripos($TGNAME, "fire") !== false ) {return 8;
	}elseif (stripos($TGNAME, "ftac") !== false) {return 8;
	}elseif (stripos($TGNAME, "tac") !== false && stripos($TGNAME, "fd") !== false ) {return 8;

	}elseif (stripos($TGNAME, "tac") !== false && stripos($TGNAME, "med") !== false ) {return 9;
	}elseif (stripos($TGNAME, "etac") !== false) {return 9;
	}elseif (stripos($TGNAME, "tac") !== false && stripos($TGNAME, "ems") !== false ) {return 9;

	}elseif (stripos($TGNAME, "interop") !== false) {return 11;

	}elseif (stripos($TGNAME, "hospital") !== false) {return 12;
	}elseif (stripos($TGNAME, "general") !== false) {return 12;
	}elseif (stripos($TGNAME, "health") !== false) {return 12;

	}elseif (stripos($TGNAME, "ham") !== false) {return 13;
	}elseif (stripos($TGNAME, "amateur") !== false) {return 13;

	}elseif (stripos($TGNAME, "works") !== false) {return 14;
	}elseif (stripos($TGNAME, "water") !== false) {return 14;
	}elseif (stripos($TGNAME, "rubbish") !== false) {return 14;
	}elseif (stripos($TGNAME, "trash") !== false) {return 14;
	}elseif (stripos($TGNAME, "forest") !== false) {return 14;
	}elseif (stripos($TGNAME, "power") !== false) {return 14;
	}elseif (stripos($TGNAME, "maint") !== false) {return 14;
	}elseif (stripos($TGNAME, "city hall") !== false) {return 14;
	}elseif (stripos($TGNAME, "bridge") !== false) {return 14;
	}elseif (stripos($TGNAME, "street") !== false) {return 14;
	}elseif (stripos($TGNAME, "service") !== false) {return 14;
	}elseif (stripos($TGNAME, "animal") !== false) {return 14;
	}elseif (stripos($TGNAME, "rec center") !== false) {return 14;
	}elseif (stripos($TGNAME, "recreation") !== false) {return 14;
	}elseif (stripos($TGNAME, "senior") !== false) {return 14;
	}elseif (stripos($TGNAME, "sanitation") !== false) {return 14;
	}elseif (stripos($TGNAME, "property") !== false) {return 14;
	}elseif (stripos($TGNAME, "park") !== false) {return 14;

	}elseif (stripos($TGNAME, "court") !== false || stripos($TGNAME, "correction") !== false ) {return 37;
	}elseif (stripos($TGNAME, "FBI") !== false) {return 16;
	}elseif (stripos($TGNAME, "emergency") !== false) {return 29;
	}elseif (stripos($TGNAME, "data") !== false || stripos($TGNAME, "mdt") !== false ) {return 2;

	}elseif (stripos($TGNAME, "police") !== false && stripos($TGNAME, "dispatch") !== false ) {return 2;
	}elseif (stripos($TGNAME, "pd") !== false) {return 2;
	}elseif (stripos($TGNAME, "pd") !== false && stripos($TGNAME, "dispatch") !== false ) {return 2;

	}elseif (stripos($TGNAME, "fire") !== false && stripos($TGNAME, "dispatch") !== false ) {return 3;
	}elseif (stripos($TGNAME, "fd") !== false) {return 3;
	}elseif (stripos($TGNAME, "fire") !== false) {return 3;
	}elseif (stripos($TGNAME, "fd") !== false && stripos($TGNAME, "dispatch") !== false ) {return 3;

	}elseif (stripos($TGNAME, "med") !== false && stripos($TGNAME, "dispatch") !== false ) {return 4;
	}elseif (stripos($TGNAME, "ems") !== false) {return 4;
	}elseif (stripos($TGNAME, "ems") !== false && stripos($TGNAME, "dispatch") !== false ) {return 4;

	}elseif (stripos($TGNAME, "police") !== false && stripos($TGNAME, "dispatch") !== false ) {return 2;
	}elseif (stripos($TGNAME, "police") !== false) {return 2;
	}elseif (stripos($TGNAME, "test") !== false) {return 21;
	}else{return 100; // Return imported
	}
}
?>