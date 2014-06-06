<?php
function rrAPIFetch($username,$password){
	global $config;
################################################
################################################
#				GET DATA FROM API 			   #
################################################
################################################
	try{
		/* create SOAP client */
		$client = new soapclient('http://api.radioreference.com/soap2/?wsdl');
		/* craft SOAP data input */
		$authInfo = array("appKey" => base64_decode($config['rrapikey']),
						"username" => substr($username,0,20), // RR has a 20 char limit on usernames
						"password" => substr($password,0,25),
						"version" => "12",
						"style" => "doc",);
		/* pull data */
		$getTGList = $client->getTrsTalkgroups($config['rrdbsid'],NULL,NULL,NULL,$authInfo); // Get talkgroup(indescript) names and TGIDs
		$getTGCats = $client->getTrsTalkgroupCats($config['rrdbsid'],$authInfo); // Get categories, these will be matched to talkgroups
	}catch(SoapFault $fault){
		return false;
	}
/// END SOAP CLIENT
################################################
################################################
#			PROCESS DATA FROM API 			   #
################################################
################################################
	/* simplify talkgroup categories, return array with $category[catID] = "Category Name" */
		foreach ($getTGCats as $objectNo => $singleCatObj) {
			$category[$singleCatObj->tgCid] = $singleCatObj->tgCname; // $category[19662] = "Cleveland Department of Public Utilities"
		}
		foreach ($getTGList as $TgCounter => $TGINFO) {
			/*
			$currTGID[56181] = 
			  [56181]=>
			    ["Name"]=> "Cleveland Department of Public Utilities Division of Water - Permits"
			    ["Category"]=> "Public Works"
			    ["Color"]=> "#00FF00"
			*/
			$newTGID[$TGINFO->tgDec] = array( "NAME" => $category[$TGINFO->tgCid]." ".$TGINFO->tgDescr,
												"CATEGORY" => $TGINFO->tags[0]->tagId);
		}
		ksort($newTGID);
		return $newTGID;
}

?>