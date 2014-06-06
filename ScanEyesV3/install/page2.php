<?php


?>
We'll need you to accept this before we can get started. ScanEyes can also use Radioreference to pull talkgroup information from their database, this is optional and required a Radioreference Premium account.

<form action="index.php?step=3" method="POST">
<textarea readonly cols=100 rows=24>
Copyright (c) <?php echo date('Y'); ?> SCANEYES.US

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

The Software shall be used for Good, not Evil.
ScanEyes has the ability to pull data from Radioreference.com. By accepting the terms, you also accept Radioreference.com's AUP and TOS. 

All data provided to ScanEyes via Radioreference.com is provided "as is" without warranty of any kind express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose and noninfrincement. 
In no event shall the authors or copyright holders be liable for any claim, damages or other liability, whether in action of contract, tort or otherwise arising from, out of, or in connection with the software or the use or other dealings in the software.</textarea><br>
<input type="checkbox" name="tos">I agree to the ScanEyes license agreement<br>
<input type="checkbox" name="rrtos">I agree to the Radioreference license agreement and acceptable use policy<br>
	<input type="submit" value="Next">
</form>