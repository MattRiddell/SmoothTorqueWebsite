<?php
require "header.php";
require("class.phpmailer.php");
$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host = $config_values['SMTP_HOST']; // SMTP server
$mail->From = $config_values['SMTP_FROM'];
$mail->AddAddress("matt@venturevoip.com");

$mail->Subject = $config_values['TITLE']." Test Message";
$mail->Body = "Hi! \n\n This is a test e-mail sent from ".$config_values['TITLE'].".";
$mail->WordWrap = 80;

if(!$mail->Send())
{
   echo 'Message was not sent.';
   echo 'Mailer error: ' . $mail->ErrorInfo;
}
else
{
   echo 'Message has been sent.';
}
?>
