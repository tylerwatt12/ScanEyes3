<?php
include('../libraries/spec-unitrunker.php');
#########################################################################
/////////////			IMPORT TALKGROUPS DATABASE 			/////////////
#########################################################################
if (@$_FILES['xml']["tmp_name"]) {
	$talkgroupsClass = new PDO('sqlite:../../database/talkgroups.sqlite');
	$talkgroups = parseUTXML($_FILES['xml']["tmp_name"],"talkgroups"); //parse xml
	foreach ($talkgroups as $TGID => $valueArray) { // Guess gategory IDs from keywords in talkgroup name
		$talkgroups[$TGID]["CATEGORY"] = autoTagCategories($valueArray["NAME"]);
	}
	$radioIDS = parseUTXML($_FILES['xml']["tmp_name"],"radioids"); //parse xml

	$date = date('Y-m-d');
	$talkgroupsClass->beginTransaction();// Start transaction
	foreach ($talkgroups as $TGID => $array) {
		$statement =   "INSERT INTO TGRELATE(TGID, NAME, COMMENT, TAG) VALUES ('{$TGID}', '{$array['NAME']}', 'Added: {$date}', '{$array['CATEGORY']}'); ";
		$talkgroupsClass->query($statement); // run the query
	}
	$talkgroupsClass->commit();// commit transaction	TGIDS
	$talkgroupsClass->beginTransaction();// Start transaction
	foreach ($radioIDS as $RID => $name) {
		$statement =   "INSERT INTO RIDRELATE(RID, NAME, COMMENT) VALUES ('{$RID}', '{$name['NAME']}', 'Added: {$date}'); ";
		$talkgroupsClass->query($statement); // run the query
	}
	$talkgroupsClass->commit();// commit transaction	RIDS
	unlink($_FILES['xml']["tmp_name"]); // Delete temp file
}
?>
<html>
	<head>
		<title>ScanEyes Install Step 5/5</title>
		<link rel="stylesheet" type="text/css" href="assets/style5.css">
	</head>
	<body>
		

		<form id="msform" action="../index.php">
			<ul id="progressbar">
				<li class="active">Install Finished</li>
			</ul>
			<fieldset>
				<h2 class="fs-title">Setup is complete, you can log in now</h2>
				<h3 class="fs-subtitle"><?php echo @$result; ?></h3>
				
				<input class="action-button" type="submit" value="Home">
			</fieldset>
		</form>
	</body>
</html>