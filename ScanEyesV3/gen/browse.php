<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-read.php';
secReq($config['mintgidbrowselvl']);
$dateToQuery = numOnly($_GET['browsedate']);
$dateName = date('l, F nS, Y',strtotime($_GET['browsedate']));
$TGS = getDateCalls($dateToQuery); //fetch call occurances
$TGNames = getTGList();
?>
<h1>Calls from <?php echo $dateName; ?></h1>
<table id="talkgroups">
	<thead>
		<th>Calls</th>
		<th>TGID</th>
		<th>TGName</th>
	</thead>
	<tbody>
	<?php 
		foreach ($TGS as $TGID => $calls) {
			if (!@$TGNames[$TGID]['NAME']) {
				$displayTGName = "Unknown"; // If TGID isn't in name DB
			}else{
				$displayTGName = $TGNames[$TGID]['NAME'];
			}
			echo "	<tr>
						<td>{$calls}</td>
						<td><a href='?page=tgid&TGID={$TGID}&date={$dateToQuery}'>{$TGID}</a></td>
						<td>{$displayTGName}</td>
					</tr>";
		}
	?>
		
	</tbody>
</table>