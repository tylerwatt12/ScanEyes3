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
In no event shall the authors or copyright holders be liable for any claim, damages or other liability, whether in action of contract, tort or otherwise arising from, out of, or in connection with the software or the use or other dealings in the software.

MIT Software License

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of SCANEYES nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL TYLER WATTHANAPHAND BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.</textarea><br>
				<div style="text-align:left; margin-left:40px;">
				<input type="checkbox" name="tos">  I agree to the ScanEyes license agreement<br>
				<span><input type="checkbox" name="rrtos">  I agree to the Radioreference license agreement and acceptable use policy</span><br>
				</div>
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