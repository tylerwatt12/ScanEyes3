<?php
	if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
		exit();
	}
	foreach ($totalTGIDS as $TGID => $rubbish) {
		// Don't use totalTGIDS as a datasource, only as a key reference source
		if (@$modifiedTGIDS[$TGID]) {$cellcolor = " bgcolor='#96aff0' title='Update talkgroup' ";   $checkboxvalue= " value='m' ";}
		if (@$deletedTGIDS[$TGID])  {$cellcolor = " bgcolor='#f09696' title='Delete talkgroup' ";   $checkboxvalue= " value='r' ";}
		if (@$newRRTGIDS[$TGID])    {$cellcolor = " bgcolor='#c8fbc4' title='Add talkgroup' ";   $checkboxvalue= " value='a' ";}
		if (@$checkboxvalue == " value='a' " || @$checkboxvalue == " value='m' ") {$checkbox = "checked";} // If new talkgroup, removed talkgroup, or updated talkgroup, enable check box, else disable checkbox
		echo "<tr>";
		echo "<td".@$cellcolor."><center>";
			if(@$checkboxvalue){echo "<input name='".$TGID."' type='checkbox' ".@$checkbox.@$checkboxvalue." >";}else{}
		echo "</center></td>";
		echo "<td>".@$TGID."</td>";
		echo "<td>".@$curTGID[$TGID]['NAME']."</td>";
		
		echo "<td bgcolor='".@$tagIDs[$curTGID[$TGID]['CATEGORY']]['COLOR']."'>".@$tagIDs[$curTGID[$TGID]['CATEGORY']]['TAG']."</td>";
		echo "<td>".@$newTGID[$TGID]['NAME']."</td>";
		echo "<td bgcolor='".@$tagIDs[$newTGID[$TGID]['CATEGORY']]['COLOR']."'>".@$tagIDs[$newTGID[$TGID]['CATEGORY']]['TAG']."</td>";
		echo "</tr>";
		unset($cellcolor);
		unset($checkboxvalue);
		unset($checkbox);
	}
?>