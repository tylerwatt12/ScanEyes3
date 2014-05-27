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
function sendAuthEmail($regEMail,$regPwSalt){
	$result = sendMail("ScanEyes Activation E-Mail","Here is your activation code: ".$regPwSalt,$regEMail);
	if ($result == "success") {
		return "an email has been sent to ".$regEMail." with your activation code.";
	}else{
		return "There was an internal E-Mail error";
	}
}
?>