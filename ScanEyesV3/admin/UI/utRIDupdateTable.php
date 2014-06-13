<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
?>
<form action="?page=utupdaterid" method="POST" >
	<div class="col-lg-14">
		<center>
			<h1>Unitrunker Radio batch update</h1>
			<table width="100%" id="talkgroups">
				<thead>
					<tr>
						<th><input type='checkbox' name='checkall' onclick='checkedAll();'></th>
						<th>ID</th>
						<th>Current Name</th>
						<th>New Name</th>
						
					</tr>
				</thead>
				<tbody>
						<?php
							foreach ($totalRIDS as $RID => $rubbish) {
								// Don't use totalRIDS as a datasource, only as a key reference source
								if (@$modifiedRIDS[$RID]) {$cellcolor = " bgcolor='#96aff0' title='Update talkgroup' ";   $checkboxvalue= " value='m' ";}
								if (@$deletedRIDS[$RID])  {$cellcolor = " bgcolor='#f09696' title='Delete talkgroup' ";   $checkboxvalue= " value='r' ";}
								if (@$newRRRIDS[$RID])    {$cellcolor = " bgcolor='#c8fbc4' title='Add talkgroup' ";   $checkboxvalue= " value='a' ";}
								if (@$checkboxvalue == " value='a' " || @$checkboxvalue == " value='m' ") {$checkbox = "checked";} // If new talkgroup, removed talkgroup, or updated talkgroup, enable check box, else disable checkbox
								echo "<tr>";
								echo "<td".@$cellcolor."><center>";
									if(@$checkboxvalue){echo "<input name='".$RID."' type='checkbox' ".@$checkbox.$checkboxvalue." >";}else{} //checbox
								echo "</center></td>";
								echo "<td>".$RID."</td>";
									echo "<td>".@$curRID[$RID]['NAME']."</td>";
									echo "<td>".@$newRID[$RID]['NAME']."</td>";
								echo "</tr>";
								unset($cellcolor);
								unset($checkboxvalue);
								unset($checkbox);
							}
						?>
					
				</tbody>
			</table>
		</center>
	</div>
	<div class="col-lg-6">
				<input type="hidden" name="xmlfile" value="<?php echo $filename; ?>">
		<input type="submit" value="Confirm">
	</div>
</form>