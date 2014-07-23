<?php
function contextSearch($query,$datatype){
	// This function requires a query, a datatype and returns callIDs
	// Supported datatypes
	/*
		TGID : 12345 (int, maxlen 10)
		RID: 1234567 (int, maxlen 10)
		TGNAME: North Royalton Fire Dispatch (char, nocase, maxlen 50)
		RNAME: Unit 55 (char, nocase, maxlen 50)
		DRANGE: 20140506-20140511 (char, maxlen 21) use strtotime
		COMMENT (char, maxlen 256) search TGRELATE, RIDRELATE, {$date}
		%%Implement later%% Search by category
	*/
	$datatypes= array("TGID","RID","TGNAME","RNAME","DRANGE","COMMENT");
	foreach ($datatypes as $loopDataType) {
		if ($loopDataType == $datatype) {
			# code...
			echo "success";
			return;
		}else{ // Internal error, or user tried hacking page
			growl("error","Internal error");
			exit();
		}
	}
}
?>