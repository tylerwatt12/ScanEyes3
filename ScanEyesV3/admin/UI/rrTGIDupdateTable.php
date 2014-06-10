<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
?>
<form action="?page=rrupdatetgid" method="POST">
	<div class="col-lg-14">
		<center>
			<h1>Radioreference Talkgroup batch update</h1>
			<table width="100%" id="talkgroups">
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
							include('unifiedUpdateTable.php');
						?>
					
				</tbody>
			</table>
		</center>
	</div>
	<div class="col-lg-6">
				<input type="hidden" name="rrdbUsername" value="<?php echo htmlspecialchars(substr($_POST['rrdbUsername'],0,20), ENT_QUOTES, 'UTF-8'); ?>">
				<input type="hidden" name="rrdbPassword" value="<?php echo htmlspecialchars(substr($_POST['rrdbPassword'],0,25), ENT_QUOTES, 'UTF-8'); ?>">
		<input type="submit" value="Confirm">
	</div>
</form>