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
include 'libraries/mp3-info.php';
secReq($config['mintgidbrowselvl']);
$maxrpp = $config['maxrpp']; //get maximum calls per page
if (@!$_GET['sortby']) { // If no sortby specificed
	$_GET['sortby'] = "UNIXTS";
}
if (@!$_GET['order']) { // If no sortby specificed
	$_GET['order'] = "desc";
}
if (@!$_GET['offset']) { // If no offset specificed
	$_GET['offset'] = 0;
}
if (@!$_GET['list']) { // If no number of calls specified
	$_GET['list'] = $maxrpp;
}
if ($_GET['list'] > $maxrpp) { // If wanted number of calls is over maximum
	growl("warning","Showing only ".$maxrpp." calls");
	$_GET['list'] = $maxrpp; //set number of calls to max allowed
}else{
	$_GET['offset'] = numOnly(substr($_GET['offset'],0,3)); //otherwise set number of calls to user input
}


$dateName = date('l, F jS, Y',strtotime($_GET['date']));
$dashedDate = date('Y-m-d',strtotime($_GET['date']));
$TGID = numOnly($_GET['TGID']);
$date = date('Ymd',strtotime($_GET['date']));
#$date = numOnly($_GET['date']);
$offset = numOnly($_GET['offset']);
$list = numOnly($_GET['list']);
$sortby = charOnly($_GET['sortby']);
$order = charOnly($_GET['order']);
$calls = getCallList($TGID,$date,$offset,$list,$sortby,$order); //Gets list of calls that match 
if (empty(getTGlist()[$TGID]['NAME']) == TRUE) { // if there is no TGName, use TGID
	$TGName =$TGID;
}else{
	$TGName = getTGlist()[$TGID]['NAME'];
}

$RID = getRList();

#for form
$htmlsafevarList = htmlspecialchars(numOnly($_GET['list']));
$htmlsafevarOffset = htmlspecialchars(numOnly($_GET['offset']));
if ($order == "asc") {$htmlformOrderAscending = "selected";}else{$htmlformOrderAscending="";}
if ($order == "desc") {$htmlformOrderDescending = "selected";}else{$htmlformOrderDescending="";}
if ($sortby == "UNIXTS"){$htmlformSortbyUNIXTS = "selected";}else{$htmlformSortbyUNIXTS="";}
if ($sortby == "RID"){$htmlformSortbyRID = "selected";}else{$htmlformSortbyRID="";}
if ($sortby == "LENGTH"){$htmlformSortbyLENGTH = "selected";}else{$htmlformSortbyLENGTH="";}
if ($sortby == "COMMENT"){$htmlformSortbyCOMMENT = "selected";}else{$htmlformSortbyCOMMENT="";}

?>
<?php
	#paginator
		if ($offset-$list > -1) { // If there are more prev pages
			$prevpage = $offset-$list;
			echo "<a href='?page=tgid&TGID={$TGID}&date={$date}&list={$list}&offset={$prevpage}&sortby={$sortby}&order={$order}'>Previous page </a>";
		}
		if ($list+$offset < $calls['COUNT']) { // If there are more pages
			$nextpage = $offset+$list;
			echo "<a href='?page=tgid&TGID={$TGID}&date={$date}&list={$list}&offset={$nextpage}&sortby={$sortby}&order={$order}'>Next page </a>";
		}
	#sort by
		echo "<form action='index.php' method='GET'>
				<input type='hidden' name='page' value='tgid'>
				<input type='hidden' name='TGID' value='56427'>
				<input type='hidden' name='date' value='20140711'>
				Show <input type='number' name='list' step='5' min='0' value='{$htmlsafevarList}' max='{$maxrpp}'> results
				Start at result <input type='number' name='offset' min='0' value='{$htmlsafevarOffset}' max='{$maxrpp}'>
				Order by <select name='sortby'>
					<option {$htmlformSortbyUNIXTS} value='UNIXTS'>UNIX timestamp</option>
					<option {$htmlformSortbyRID} value='RID'>Source ID/RID</option>
					<option {$htmlformSortbyLENGTH} value='LENGTH' disabled>Call length</option>
					<option {$htmlformSortbyCOMMENT} value='COMMENT'>Call comment alpbetical</option>
				</select>
				<select name='order'>
					<option {$htmlformOrderAscending} value='asc'>Ascending</option>
					<option {$htmlformOrderDescending} value='desc'>Descending</option>
				</select>
				<input type='submit'>
			</form>

		";
?>
<h4><?php echo "Talkgroups on ".$TGName." from ".$dateName; ?></h4>
<h5><?php if (($offset+$list) > $calls['COUNT']) {$top = $calls['COUNT'];}else{$top = ($offset+$list);}
		  echo(($offset+1)."-".($top)." of ".$calls['COUNT']);?></h5>
<table id="calls">
	<thead>
		<th>CID</th>
		<th>Time</th>
		<th>Length</th>
		<th>Source</th>
		<th>Comment</th>
	</thead>
	<tbody>
		<?php
			if (@$calls) {
				foreach ($calls['DATA'] as $CID => $valueArray) {
					$time = date('h:i:s A',substr($CID,0,10));
					$tz  = date('P e');
					$playurl = "?page=playcall&CID={$CID}";
					$mp3FileLocal = $config['sccallsavedir'].'/'.$dashedDate.'/'.$CID.$config['sndext'];
					$ago = ago(substr($CID, 0, 10));
					if (is_file($mp3FileLocal)) {// If file exists in folder
						$mp3Handle = new mp3file($mp3FileLocal);
						@$length = $mp3Handle->get_metadata()['Length'];
							if (@$length) { // If call is over 0 seconds long, show call
								if (@!$RID[$valueArray['RID']]) { // If no formal RID name found, make RID unknown
									$ridname = $valueArray['RID'];
								}else{
									$ridname = $RID[$valueArray['RID']]['NAME'];
								}	
								echo "	<tr>
											<td style='text-align:center'><a href='{$playurl}' target='_blank'>{$CID}</a></td>
											<td style='text-align:center' title='{$tz}&#13;{$CID}&#13;{$ago}'>{$time}</td>
											<td style='text-align:left'>{$length}</td>
											<td style='text-align:left' title='{$valueArray['RID']}'>{$ridname}</td>
											<td style='text-align:left'>{$valueArray['COMMENT']}</td>
										</tr>";
							}
					}
					
				}
			}
		?>
	</tbody>
</table>
<small>Calls under 1 second long will not be shown.</small>