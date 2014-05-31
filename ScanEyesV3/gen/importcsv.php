<?php
include 'libraries/db-write.php';
include 'libraries/gen-gen.php';
/* 
This file imports from the RR DB into calls.db->TGRELATE
If TGs are found that aren't already in the SQLite DB, it will automatically add them
If TGs are found with identical TGID and descriptions, they will be skipped
If TGs are found with differing descriptions, the user will have the option to overwrite their existing definitions
RR premium account needed
Alternate import method: uncomment deprecated snippet, comment out the soap client.
Download [SID].csv from radioreference and put it into /static/. Make sure your $config['rrdbsid'] matches the [SID].csv file
*/
if ($_SESSION['usrlvl'] < 3) { // If user isn't a DB admin or higher, stop page load
	exit();
}
if (@!$_POST['rrdbUsername'] && @!$_POST['rrdbPassword']) { // If there was
	echo '<b>Please log into Radioreference</b>
			<form method="POST">
				<input type="text" name="rrdbUsername">
				<input type="password" name="rrdbPassword">
				<input type="submit" value="login">
			</form>';
	exit();
}
$startTime = time(); // Start timer

/// SOAP CLIENT
	try{
		/* create SOAP client */
		$client = new soapclient('http://api.radioreference.com/soap2/?wsdl');
		/* craft SOAP data input */
		$authInfo = array("appKey" => $config['rrapikey'],
						"username" => substr($_POST['rrdbUsername'],0,50),
						"password" => substr($_POST['rrdbPassword'],0,25),
						"version" => "12",
						"style" => "doc",);
		/* pull data */
		$getTGList = $client->getTrsTalkgroups('7337',NULL,NULL,NULL,$authInfo); // Get talkgroup(indescript) names and TGIDs
		$getTGCats = $client->getTrsTalkgroupCats('7337',$authInfo); // Get categories, these will be matched to talkgroups
	}catch(SoapFault $fault){
		echo"Bad username or password";
		exit;
	}
	/* simplify talkgroup categories, return array with $category[catID] = "Category Name" */
	foreach ($getTGCats as $objectNo => $singleCatObj) {
		$category[$singleCatObj->tgCid] = $singleCatObj->tgCname; // $category[19662] = "Cleveland Department of Public Utilities"
	}
	foreach ($getTGList as $TgCounter => $TGINFO) {
		$talkGroup[$TGINFO->tgDec] = $category[$TGINFO->tgCid]." ".$TGINFO->tgDescr; // $talkGroup[56427] = "North Royalton Police Dispatch"
	}
/// END SOAP CLIENT


/*							THIS METHOD IS DEPRECATED BUT WILL STILL WORK UNCOMMENTED					###
if (@is_file("static/".$config['rrdbsid'].".csv") == false) {
	echo $config['rrdbsid'].".csv not found in ./static/";
	exit();
}
$csv = file_get_contents("static/".$config['rrdbsid'].".csv"); // Read CSV file
$csvLines = explode("\n", $csv); // turn long CSV string into array
unset($csvLines[0]); // remove CSV header field names
unset($csvLines[sizeof($csvLines)]); //remove last line from CSV
foreach ($csvLines as $lineValue) { // Make array called talkGroup, array key = TGID, value = TGName
	@list($decimal,$subfleet,$alphaTag,$mode,$desc,$serviceTag,$category) = explode(",", $lineValue);
	$name = $category." ".$desc; // Make full talkgroup name, combine the category and talkgroup description
	$talkGroup[$decimal] = $name; // Set array talkGroup, key = TGID, value, NAME
}
*/

$db = new callsDB(); // Call database instance
$db->busyTimeout(5000);
$result = $db->query("SELECT * FROM TGRELATE"); // Select all the TGIDs from the DB
unset($db); // unlock database
while($res = $result->fetchArray(SQLITE3_ASSOC)){ //While there are SQL returned entries,
 if(!isset($res['TGID'])) continue;  //If there are no more values, kill loop
  $localdb[$res['TGID']] = str_replace("\n", '',$res['NAME']); //Set array, $localdb, key TGID, value TGNAME
}

$compiledStatement = "";// set SQL placeholder
$today = date('Y-m-d'); // for 3rd column of TGRELATE table
foreach ($talkGroup as $TGID => $csvdbName) { // for every local TGID
	If (array_key_exists($TGID, $localdb) && $csvdbName == $localdb[$TGID]){ //If the TGID exists in current local DB and TGNames are identical
		$skippedIdenticals[$TGID] = $csvdbName;
	}elseif(array_key_exists($TGID, $localdb) && $csvdbName !== $localdb[$TGID]){ //If the TGID exists in local, but TGNames are different
		//Warn user to overwrite, compile into overwrite array.
		if (@$_GET['overwriteall'] = 1) {//If user said overwrite was okay
			$compiledStatement.= "UPDATE TGRELATE SET NAME='{$csvdbName}',COMMENT='updated: {$today}' WHERE TGID='{$TGID}'; ";
			$added[$TGID] = $csvdbName;
		}else{
			$overwriteTo[$TGID] = $csvdbName; // New TG name the script wants to write
			$overwriteFrom[$TGID] = $localdb[$TGID]; // Current local database TG
		}		
	}else{
		$compiledStatement.= "INSERT INTO TGRELATE (TGID,NAME,COMMENT) VALUES ('{$TGID}','{$csvdbName}','added: {$today}'); ";
		// Insert value into TGRELATE
		$added[$TGID] = $csvdbName;
	}
}
$db = new callsDB(); // Call database instance
$db->busyTimeout(5000);
$db->exec($compiledStatement); // Execute
if ($db->lastErrorMsg() !== "not an error") {
		// other uncaught error
		echo "Unhandled exception: ".$db->lastErrorMsg();
	}elseif ($db->lastErrorMsg() == "not an error") {
		$timeTaken = time() - $startTime;
		echo "Database written successfully in ".$timeTaken." seconds";
	}
unset($db); // unlock database
//Report generation
/*
RETURNS WITH
--overwrite(not a variable)-- = talkgroups with conflicts
$overwriteTo = what the script wants to replace them with
$overwriteFrom = what the talkgroup values are currently before overwriting

$added = talkgroups added that are not in the database

$skippedIdenticals = talkgroups skipped because they have the same value as before
*/
?>
<h1>Added/confirmed updated talkgroups</h1>
<table border=1>
	<thead>
		<tr>
			<th>TGID</th>
			<th>Name</th>
	</thead>
	<tbody>
		<?php
			if (@$added) {
				foreach ($added as $TGID => $name) {
					echo "<tr><td>".$TGID."</td><td>".$name."</td></tr>";
				}
			}
		?>
	</tbody>
</table>
<h1>Talkgroups that were not overwritten</h1><a href="?page=importcsv&overwriteall=1">Overwrite All</a>
<table border=1>
	<thead>
		<tr>
			<th>TGID</th>
			<th>From</th>
			<th>To</th>
	</thead>
	<tbody>
		<?php
			if (@$overwriteFrom) {
				foreach ($overwriteFrom as $TGID => $name) {
					echo "<tr><td>".$TGID."</td><td>".$name."</td><td>".$overwriteTo[$TGID]."</td></tr>";
				}
			}
		?>
	</tbody>
</table>
<h1>Skipped talkgroups</h1>
<table border=1>
	<thead>
		<tr>
			<th>TGID</th>
			<th>Name</th>
	</thead>
	<tbody>
		<?php
			if (@$skippedIdenticals) {
				foreach ($skippedIdenticals as $TGID => $name) {
					echo "<tr><td>".$TGID."</td><td>".$name."</td></tr>";
				}
			}
		?>
	</tbody>
</table>