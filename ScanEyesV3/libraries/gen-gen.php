<?php
function sendMail($title,$body,$recip){
	require 'libraries/phpmailer/PHPMailerAutoload.php'; //Load PHPMailer library

	global $config; // Load email address and password from config file from DB

	$mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = "ssl"; // secure transfer enabled REQUIRED for GMail
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = $config['gmailaddr'];
	$mail->Password = $config['gmailpass'];
	$mail->SetFrom($config['gmailaddr']);
	$mail->WordWrap = 50; 
	$mail->isHTML(true);   
	$mail->Subject = $title;
	$mail->Body = $body;
	$mail->AddAddress($recip);
	 if(!$mail->Send()){
		return "error";
		#echo "Mailer Error: " . $mail->ErrorInfo;
	}else{
		#echo "Message has been sent";
		return "success";
	}
}
function sendAuthEmail($regUname,$regEMail,$regPwSalt){
	global $config;
	$_SESSION['reputation']--;
	$result = sendMail("ScanEyes Activation E-Mail","Here is your activation code: ".$regPwSalt."
		<br>Alternatively, you can click <a href=\"".$config['httpmethod'].$config['domain']."/?page=auth&username=".$regUname."&code=".$regPwSalt."\">here</a>"
	,$regEMail);
	if ($result == "success") {
		return "an email has been sent to ".$regEMail." with your activation code.";
	}else{
		return false;
	}
}
function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
 }
 function growl($title,$message){
 	/*
	Supported Datatypes
	growl("warning","you best check yo-self")
	growl("notice","Turn down for what?")
	growl("error","Ion distribution chamber init failed")
	growl("randomtitle","just a regular message")
	
 	*/
 	if ($title == "warning") {
 		echo"<script type='text/javascript'>
				$.growl.warning({ message: '{$message}' });
			</script>";
 	}elseif ($title == "notice") {
 		echo"<script type='text/javascript'>
				$.growl.notice({ message: '{$message}' });
			</script>";
 	}elseif ($title == "error") {
 		echo"<script type='text/javascript'>
				$.growl.error({ message: '{$message}' });
			</script>";	  
 	}else{
 		echo"<script type='text/javascript'>
			  $.growl({ title: '{$title}', message: '{$message}' });
			</script>";
 	}
 	
 }
?>