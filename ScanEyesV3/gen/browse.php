<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
if (@!$_GET['browsedate']) {
	growl("error","internal error");
	exit();
}
include 'libraries/db-read.php';
secReq($config['mintgidbrowselvl']);
$dateToQuery = date('Ymd',strtotime($_GET['browsedate']));
$dateName = date('l, F jS, Y',strtotime($_GET['browsedate']));
$TGS = getDateCalls($dateToQuery); //fetch call occurances
$TGNames = getTGList();
?>
<h1>Calls from <?php echo $dateName; ?></h1>
<table id="talkgroups">
	<thead>
		<th>Calls</th>
		<th>Talkgroup</th>
	</thead>
	<tbody>
	<?php 
		foreach ($TGS as $TGID => $calls) {
			if (!@$TGNames[$TGID]['NAME']) {
				$displayTGName = $TGID; // If TGID isn't in name DB
			}else{
				$displayTGName = $TGNames[$TGID]['NAME'];
			}
			echo "	<tr>
						<td>{$calls}</td>
						<td title='{$TGID}'><a href='?page=tgid&TGID={$TGID}&date={$dateToQuery}'>{$displayTGName}</a></td>
					</tr>";
		}
	?>
		
	</tbody>
</table>
<a href='?page=browse&browsedate=<?php echo date('Y-m-d',strtotime("-1 month",strtotime($_GET['browsedate']))); ?>'>-1 month</a>
<a href='?page=browse&browsedate=<?php echo date('Y-m-d',strtotime("-1 week",strtotime($_GET['browsedate']))); ?>'>-1 week</a>
<a href='?page=browse&browsedate=<?php echo date('Y-m-d',strtotime("-1 day",strtotime($_GET['browsedate']))); ?>'>-1 day</a>
<a href='?page=browse&browsedate=<?php echo date('Y-m-d',strtotime("+1 day",strtotime($_GET['browsedate']))); ?>'>+1 day</a>
<a href='?page=browse&browsedate=<?php echo date('Y-m-d',strtotime("+1 week",strtotime($_GET['browsedate']))); ?>'>+1 week</a>
<a href='?page=browse&browsedate=<?php echo date('Y-m-d',strtotime("+1 month",strtotime($_GET['browsedate']))); ?>'>+1 month</a>