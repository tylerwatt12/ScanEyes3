<?php


?>
<html>
	<head>
		<title>ScanEyes Install Step 2/5</title>
		<link rel="stylesheet" type="text/css" href="assets/style2.css">
	</head>
	<body>
		

		<form action="index.php?step=3" id="msform" method="POST">
			<ul id="progressbar">
				<li class="active">License Agreement</li>
			</ul>
			<fieldset>
				<h2 class="fs-title">ScanEyes Setup Step 2/5</h2>
				<h3 class="fs-subtitle">We'll need you to accept this before we can get started.</h3>
				<h3 class="fs-subtitle">ScanEyes can also use Radioreference to pull talkgroup information from their database, this is optional and required a Radioreference Premium account.</h3>
				<textarea readonly cols="80" rows="24">
Copyright (c) <?php echo date('Y'); ?> SCANEYES.US

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

The Software shall be used for Good, not Evil.
ScanEyes has the ability to pull data from Radioreference.com. By accepting the terms, you also accept Radioreference.com's AUP and TOS. 

All data provided to ScanEyes via Radioreference.com is provided "as is" without warranty of any kind express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose and noninfrincement. 
In no event shall the authors or copyright holders be liable for any claim, damages or other liability, whether in action of contract, tort or otherwise arising from, out of, or in connection with the software or the use or other dealings in the software.</textarea><br>
				<input type="checkbox" name="tos">  I agree to the ScanEyes license agreement<br>
				<span><input type="checkbox" name="rrtos">  I agree to the Radioreference license agreement and acceptable use policy</span><br>
				<input type="submit" name="submit" class="action-button" value="Next" />
			</fieldset>
		</form>
		<!-- jQuery -->
		<script src="http://thecodeplayer.com/uploads/js/jquery-1.9.1.min.js" type="text/javascript"></script>
		<!-- jQuery easing plugin -->
		<script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>
		<script language="javascript" type="text/javascript" src="assets/paginator.js"></script>
	</body>
</html>