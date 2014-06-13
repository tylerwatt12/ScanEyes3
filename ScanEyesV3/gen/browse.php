<?php
include 'libraries/db-write.php';
include 'libraries/db-read.php';
secReq($config['mintgidbrowselvl']);
$dateToQuery = numOnly($_GET['browsedate']);
$dateName = date('l, F nS, Y',strtotime($_GET['browsedate']));
echo $dateName;
?>
<h1>Calls from <?php echo $dateName; ?></h1>
<table border=1>
	<thead>
		<th>Calls</th>
		<th>TGID</th>
		<th>TGName</th>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>