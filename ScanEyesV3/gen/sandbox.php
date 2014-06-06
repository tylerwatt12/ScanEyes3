<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
#########################################################################
$db = new talkgroupsDB(); // Call database instance
$db->busyTimeout(5000); // Create tables for RIDs, TGIDs, and TAGs
$db->exec("INSERT INTO TAG(ID, TAG, COLOR) VALUES (1, 'Multi-Dispatch', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (2, 'Law Dispatch', '#0000FF');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (3, 'Fire Dispatch', '#FF0000');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (4, 'EMS Dispatch', '#FFAA00');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (6, 'Multi-Tac', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (7, 'Law Tac', '#0000EE');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (8, 'Fire-Tac', '#EE0000');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (9, 'EMS-Tac', '#FFAA00');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (11, 'Interop', '#550000');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (12, 'Hospital', '#FF00FF');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (13, 'Ham', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (14, 'Public Works', '#00FF00');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (15, 'Aircraft', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (16, 'Federal', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (17, 'Business', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (20, 'Railroad', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (21, 'Other', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (22, 'Multi-Talk', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (23, 'Law Talk', '#0000DD');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (24, 'Fire-Talk', '#DD0000');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (25, 'EMS-Talk', '#FFAA00');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (26, 'Transportation', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (29, 'Emergency Ops', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (30, 'Military', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (31, 'Media', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (32, 'Schools', '#FFF000');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (33, 'Security', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (34, 'Utilities', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (35, 'Data', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (36, 'Deprecated', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (37, 'Corrections', '#808080');
		INSERT INTO TAG(ID, TAG, COLOR) VALUES (100, 'Imported', '#808080'); ");
unset($db);
?>
