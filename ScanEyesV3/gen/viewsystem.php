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
<div class="col-lg-8">
	<h1>Talkgroups</h1>
	<table id="talkgroups" width="100%">
		<thead>
			<tr>
				<th>DEC</th>
				<th>Name<?php if($_SESSION['usrlvl'] > 2){echo" <a target='_blank' href='?page=addtgid' title='Add TGID'><img src='static/icons/addtgid.png'></a>
																<a target='_blank' href='?page=deltgid' title='Delete TGID'><img src='static/icons/delete.png'></a>
																<a target='_blank' href='?page=importrrtgid' title='Import Talkgroups from RadioReference'><img src='static/icons/rrimport.png'></a>
																<a target='_blank' href='?page=importuttgid' title='Import Talkgroups from Unitrunker'><img src='static/icons/utimport.png'></a>";}?></th>
				<th>Category<?php if($_SESSION['usrlvl'] > 2){echo" <a target='_blank' href='?page=addcategory' title='Add Category'><img src='static/icons/addcategory.png'></a>
																	<a target='_blank' href='?page=delcategory' title='Delete Category'><img src='static/icons/delcategory.png'></a>
																	<a target='_blank' href='?page=editcategory' title='Modify Category'><img src='static/icons/modcategory.png'></a>";}?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			$tagIDs = getTGTags();
			if (@$talkgroups) {
				foreach ($talkgroups as $TGID => $infoArray) {
					if (@!$tagIDs[$infoArray['CATEGORY']]['TAG'] || @!$tagIDs[$infoArray['CATEGORY']]['COLOR']) {
						$tagIDs[$infoArray['CATEGORY']]['TAG'] = $infoArray['CATEGORY'];
						$tagIDs[$infoArray['CATEGORY']]['COLOR'] = "#FF0000";
					}
					echo"<tr>
							<td>{$TGID}</td>
							<td>";
							if($_SESSION['usrlvl'] > 2){ echo "<a target='_blank' href='?page=edittgid&TGID={$TGID}'><img src='static/icons/modify.png'> </a>
															   <a target='_blank' href='?page=deltgid&TGID={$TGID}'><img src='static/icons/delete.png'> </a>";}
							echo $infoArray['NAME'];
							echo "</td>
							<td title='{$infoArray['COMMENT']}' bgcolor='{$tagIDs[$infoArray['CATEGORY']]['COLOR']}'>{$tagIDs[$infoArray['CATEGORY']]['TAG']}</td>
						</tr>";
				}
			}else{
				echo"<tr><td>No TGIDs</td><td>Add some with <a href='?page=importuttgid'>Unitrunker</a> or <a href='?page=importrrtgid'>Radioreference</a></td></tr>";
			}
		?>
		</tbody>
	</table>
</div>
<div class="col-lg-4">
	<h1>Radios</h1>
	<table id="radioids" width="100%">
		<thead>
			<tr>
				<th>DEC</th>
				<th>Name<?php if($_SESSION['usrlvl'] > 2){echo" <a target='_blank' href='?page=addrid'><img src='static/icons/addrid.png' title='Add RID'></a>
																<a target='_blank' href='?page=delrid'><img src='static/icons/delete.png' title='Delete RID'></a>
																<a target='_blank' href='?page=importutrid'><img src='static/icons/utimport.png' title='Import RID from Unitrunker'></a>";}?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			if (@$radioids) {
				foreach ($radioids as $RID => $infoArray) {
					echo"<tr>
							<td>{$RID}</td>
							<td title='{$infoArray['COMMENT']}'>";
					if($_SESSION['usrlvl'] > 2){ echo "<a target='_blank' href='?page=editrid&RID={$RID}'><img src='static/icons/modify.png'> </a>
													   <a target='_blank' href='?page=delrid&RID={$RID}'><img src='static/icons/delete.png'> </a>";}
					echo "{$infoArray['NAME']}</td>
						</tr>";
				}
			}else{
				echo"<tr><td>No RIDs</td><td>Add some with <a href='?page=importutrid'>Unitrunker</a></td></tr>";
			}
		?>
		</tbody>
	</table>
</div>