<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
/* 
This file shows talkgroups and users on ScanEyes
If the user is an admin, they can edit this data

Allow context based search

*/

 /*
[1823165]=>
  array(2) {
    ["NAME"]=>
    string(14) "Mike Masterson"
    ["COMMENT"]=>
    string(17) "Added: 2014-06-06"
  }
*/
 /*
 [56055]=>
  array(3) {
    ["NAME"]=>
    string(56) "Cleveland Hopkins International Airport (CLE) Electrical"
    ["CATEGORY"]=>
    int(14)
    ["COMMENT"]=>
    string(19) "Updated: 2014-06-06"
  }
*/

secReq($config['mintgidbrowselvl']); // Users 1+ can use this page
$talkgroups = getTGList();
$radioids = getRList();
?>
<div class="col-lg-12">
	<center><h1>System View</h1></center>
	<a target="_blank" href="http://www.radioreference.com/apps/db/?sid=<?php echo $config['rrdbsid']; ?>">Radioreference Link</a>
</div>
<div class="col-lg-6">
	<h1>Talkgroups</h1>
	

	<table border="1" width="100%">
		<thead>
			<tr>
				<th>DEC</th>
				<th>Name</th>
				<th>Category</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$tagIDs = getTGTags();
			foreach ($talkgroups as $TGID => $infoArray) {
				echo"<tr>
						<td>{$TGID}</td>
						<td>{$infoArray['NAME']}</td>
						<td title='{$infoArray['COMMENT']}' bgcolor='{$tagIDs[$infoArray['CATEGORY']]['COLOR']}'>{$tagIDs[$infoArray['CATEGORY']]['TAG']}</td>
					</tr>";
			}
		?>
		</tbody>
	</table>
</div>
<div class="col-lg-6">
	<h1>Radios</h1>
	<table border="1" width="100%">
		<thead>
			<tr>
				<th>DEC</th>
				<th>Name</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($radioids as $RID => $infoArray) {
				echo"<tr>
						<td>{$RID}</td>
						<td title='{$infoArray['COMMENT']}'>{$infoArray['NAME']}</td>
					</tr>";
			}
		?>
		</tbody>
	</table>
</div>