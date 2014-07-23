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
function sentDBBackup($recip,$file){
	
}
function sendAuthEmail($regUname,$regEMail,$key){
	global $config;
	$_SESSION['reputation']--;
	$result = sendMail("ScanEyes Activation E-Mail","Here is your activation code: ".$key."
		<br>Alternatively, you can click <a href=\"".$config['httpmethod'].$config['domain']."/?page=auth&username=".$regUname."&code=".$key."\">here</a>"
	,$regEMail);
	if ($result == "success") {
		return "an email has been sent to ".$regEMail." with your activation code.";
	}else{
		return false;
	}
}
function sendResetEmail($email,$key){
	$emailemail = htmlspecialchars($email); // cleaned email for HTML
	# key is 0-9a-Z, no san needed
	global $config;
	$_SESSION['reputation']--;
	$senderIP = htmlspecialchars($_SERVER['REMOTE_ADDR']);
	$result = sendMail("ScanEyes Password Reset","You or someone else has attempted resetting your password. If you intended to reset your password you can click 
						<a href='{$config['httpmethod']}{$config['domain']}/?page=pwreset&key={$key}'>here</a>. Otherwise ignore this email<br>
						This email was sent by {$senderIP}"
	,$email);
	if ($result == "success") {
		return "An email has been sent to ".$emailemail." with your Password reset instructions.";
	}else{
		return "Internal mailer error";
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

function ago($time){
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();

       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j] ago";
}
?>