<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
if (@!$_GET['TGID'] || @!$_GET['date']) {
	growl("error","no data");
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
secReq($config['mintgidbrowselvl']);
$maxrpp = $config['maxrpp']; //get maximum calls per page
if (@!$_GET['offset']) { // If no offset specificed
	$_GET['offset'] = 0;
}
if (@!$_GET['list']) { // If no number of calls specified
	$_GET['list'] = 200;
}
if ($_GET['list'] > $maxrpp) { // If wanted number of calls is over maximum
	growl("warning","Showing only ".$maxrpp." calls");
	$_GET['list'] = $maxrpp; //set number of calls to max allowed
}else{
	$_GET['offset'] = numOnly(substr($_GET['offset'],0,3)); //otherwise set number of calls to user input
}
$dateName = date('l, F nS, Y',strtotime($_GET['date']));
$TGID = numOnly($_GET['TGID']);
$date = numOnly($_GET['date']);
$offset = numOnly($_GET['offset']);
$list = numOnly($_GET['list']);
$calls = getCallList($TGID,$date,$offset,$list);
$TGName = getTGlist()[$TGID]['NAME'];
$RID = getRList();
?>
<?php 
if ($offset-$list > -1) { // If there are more prev pages
	$prevpage = $offset-$list;
	echo "<a href='?page=tgid&TGID={$TGID}&date={$date}&list={$list}&offset={$prevpage}'>Previous page </a>";
}
if ($list+$offset < $calls['COUNT']) { // If there are more pages
	$nextpage = $offset+$list;
	echo "<a href='?page=tgid&TGID={$TGID}&date={$date}&list={$list}&offset={$nextpage}'>Next page </a>";
}
?>
<h4><?php echo "Talkgroups on ".$TGName." from ".$dateName; ?></h4>
<h5><?php if (($offset+$list) > $calls['COUNT']) {$top = $calls['COUNT'];}else{$top = ($offset+$list);}
		  echo(($offset+1)."-".($top)." of ".$calls['COUNT']);?></h5>
<table id="calls">
	<thead>
		<th>Time</th>
		<th>Length</th>
		<th>RID</th>
		<th>Name</th>
	</thead>
	<tbody>
		<?php
			foreach ($calls['DATA'] as $CID => $valueArray) {
				$time = date('h:i:s A',substr($CID,0,10));
				$tz  = date('P e');
				if (@!$RID[$valueArray['RID']]) { // If no formal RID name found
					$ridname = "Unknown";
				}else{
					$ridname = $RID[$valueArray['RID']]['NAME'];
				}	
				echo "	<tr>
							<td title='{$tz}&#13;{$CID}'>{$time}</td>
							<td>Use MP3LIB</td>
							<td title='{$valueArray['COMMENT']}'>{$valueArray['RID']}</td>
							<td>{$ridname}</td>
						</tr>";
			}
		?>
	</tbody>
</table>
