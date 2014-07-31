<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
if (@!$_GET['CID']) {
	growl("error","no data");
	exit();
}
secReq($config['mintgidbrowselvl']);
include 'libraries/db-read.php';
include 'libraries/mp3-info.php';
$CID = numOnly($_GET['CID']); // Sanitize CID
$ago = ago(substr($CID, 0, 10)); // set time ago string
$dashedDate = date('Y-m-d',substr($CID,0,10)); //get dashed date for folder searching
$humanDateTime = date('l, F jS, Y. g:i:s A',substr($CID,0,10)); // make human readable date for alt text
$ffurl = $config['httpmethod'].$config['domain'].'/'.$config['sccallsavedir'].'/'.$dashedDate.'/'.$CID.$config['sndext']; //file for the HTML5 player to play
?>
<?php
foreach (getSingleCall($CID) as $object => $value) {
	$callInfo[$object] = htmlspecialchars($value);
}
if ($callInfo['UNIXTS'] == FALSE) {
	growl("error","No such call found");
	exit();
}
if (empty($callInfo['RIDNAME']) == TRUE) { // If there is no RNAME show RID
	$callInfo['RIDNAME'] = $callInfo['RID'];
}
if (empty($callInfo['TGNAME']) == TRUE) { // If there is no TGNAME show TGID
	$callInfo['TGNAME'] = $callInfo['TGID'];
}
?>
<audio autoplay controls><source src="<?php echo $ffurl; ?>" type="audio/mpeg"></audio>
<?php
echo "<table>
		<tr>
			<td>SRCID:</td>
			<td title='{$callInfo['RID']}'>{$callInfo['RIDNAME']}</td>
		</tr>
		<tr>
			<td>TGTID:</td>
			<td title='{$callInfo['TGID']}'>{$callInfo['TGNAME']}</td>
		</tr>
		<tr>
			<td>CallID:</td>
			<td>{$callInfo['UNIXTS']}</td>
		</tr>
		<tr>
			<td>Length:</td>
			<td title='{$humanDateTime}&#13;{$ago}'>{$callInfo['CALCLENGTH']} seconds</td>
		</tr>
		<tr style='background-color:{$callInfo['TGCATCOLOR']}'>
			<td title='{$callInfo['TGCATCOLOR']}'>Category: </td>
			<td title='{$callInfo['TGCATID']}'>{$callInfo['TGCATEGORY']}</td>
		</tr>
	</table>
	<span>Call Comment</span><br>
	<textarea readonly rows=1>{$callInfo['CALLCOMMENT']}</textarea><br>
	<span>Talkgroup Comment</span><br>
	<textarea readonly rows=1>{$callInfo['TGCOMMENT']}</textarea><br>
	<span>RID Comment</span><br>
	<textarea readonly rows=1>{$callInfo['RIDCOMMENT']}</textarea><br>
";