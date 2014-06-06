<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
?>
<form action="?page=rrupdatetgid" method="POST" id="talkgroups">
	<div class="col-lg-12">
		<center><h1>Talkgroup batch update</h1></center>
		<table border=1>
			<thead>
				<tr>
					<th>Color</th>
					<th>Meaning</th>
				</tr>
			</thead>
			<tbody>
				<tr bgcolor="red">
					<td>Red</td>
					<td>Delete Talkgroup</td>
				</tr>
				<tr bgcolor="green">
					<td>Green</td>
					<td>Add Talkgroup</td>
				</tr>
				<tr bgcolor="blue">
					<td>Blue</td>
					<td>Update Talkgroup</td>
				</tr>
			</tbody>
		</table>
		<table border=1 width=100%>
			<thead>
				<tr>
					<th><input type='checkbox' name='checkall' onclick='checkedAll();'></th>
					<th>ID</th>
					<th>Current Name</th>
					<th>Current Category</th>
					<th>New Name</th>
					<th>New Category</th>
				</tr>
			</thead>
			<tbody>
				
					<?php
					foreach ($totalTGIDS as $TGID => $rubbish) {
						// Don't use totalTGIDS as a datasource, only as a key reference source
						if (@$modifiedTGIDS[$TGID]) {$cellcolor = " bgcolor='blue' ";  $checkboxvalue= " value='m' ";}
						if (@$deletedTGIDS[$TGID])  {$cellcolor = " bgcolor='red' ";   $checkboxvalue= " value='r' ";}
						if (@$newRRTGIDS[$TGID])    {$cellcolor = " bgcolor='green' "; $checkboxvalue= " value='a' ";}
						if (@$cellcolor == 'blue' || @$cellcolor == 'green') {$checkbox = "checked";} // If new talkgroup, removed talkgroup, or updated talkgroup, enable check box, else disable checkbox
						echo "<tr>";
						echo "<td".@$cellcolor."><center>";
							if(@$checkboxvalue){echo "<input name='".$TGID."' type='checkbox' ".@$checkbox.@$checkboxvalue." >";}else{echo "<span class='glyphicon glyphicon-remove'></span>";}
						echo "</center></td>";
						echo "<td>".@$TGID."</td>";
						echo "<td>".@$curTGID[$TGID]['NAME']."</td>";
						
						echo "<td bgcolor='".@$tagIDs[$curTGID[$TGID]['CATEGORY']]['COLOR']."'>".@$tagIDs[$curTGID[$TGID]['CATEGORY']]['TAG']."</td>";
						echo "<td>".@$newTGID[$TGID]['NAME']."</td>";
						echo "<td bgcolor='".@$tagIDs[$newTGID[$TGID]['CATEGORY']]['COLOR']."'>".@$tagIDs[$newTGID[$TGID]['CATEGORY']]['TAG']."</td>";
						echo "</tr>";
						unset($cellcolor);
						unset($checkboxvalue);
					}
					?>
				
			</tbody>
		</table>
	</div>
	<div class="col-lg-6">
				<input type="hidden" name="rrdbUsername" value="<?php echo htmlspecialchars(substr($_POST['rrdbUsername'],0,20), ENT_QUOTES, 'UTF-8'); ?>">
				<input type="hidden" name="rrdbPassword" value="<?php echo htmlspecialchars(substr($_POST['rrdbPassword'],0,25), ENT_QUOTES, 'UTF-8'); ?>">
		<input type="submit" value="Confirm">
	</div>
</form>