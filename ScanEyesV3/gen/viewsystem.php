<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename($_SERVER['REQUEST_URI'])){
	exit();
}
include 'libraries/db-write.php';
include 'libraries/db-read.php';
include 'libraries/gen-gen.php';
/* 
This file imports from the RR DB into calls.db->TGRELATE
If TGs are found that aren't already in the SQLite DB, it will automatically add them
If TGs are found with identical TGID and descriptions, they will be skipped
If TGs are found with differing descriptions, the user will have the option to overwrite their existing definitions
RR premium account needed
*/
secReq($config['mintgidbrowselvl']); // Users 1+ can use this page

?>