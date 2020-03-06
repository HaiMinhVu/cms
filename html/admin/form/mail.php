<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
/*require 'phpmailer/vendor/autoload.php';

$mail = new PHPMailer;

//From email address and name
$mail->From = "autobot.sellmark@gmail.com";
$mail->FromName = "Full Name";

//To address and name
$mail->addAddress("hvu@sellmark.net", "hai vu");

//Address to which recipient will reply
$mail->addReplyTo("webmaster@sightmark.com", "Reply");

//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "Subject Text";
$mail->Body = "<i>Mail body in HTML</i>";
$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    echo "Message has been sent successfully";
}*/


//for gmail config
/*require 'phpmailer/vendor/autoload.php';

$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 587; 
$mail->IsHTML(true);
$mail->Username = "autobot.sellmark@gmail.com";
$mail->Password = "H@i1101989";

$mail->setFrom('autobot.sellmark@gmail.com', 'AutoBot');
//$mail->addAddress('hvu@sellmark.net', 'Hai Vu'); 
$mail->addAddress($email, $fname.' '.$lname); 


$mail->Subject = "Confirmation Email";

$mail->Body = $body;

$mail->Send();*/



// for office365 config
require_once '../phpmailer/vendor/autoload.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.office365.com';
$mail->Port       = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth   = true;
$mail->Username = $mail_config_username;
$mail->Password = $mail_config_password;
$mail->SetFrom($mail_config_username, 'Sellmark Customer Service');
$mail->addAddress($email, $fname.' '.$lname); 

if(count($proof) >= 1){
	$mail->AddAttachment($target_file);
}


$mail->IsHTML(true);



$mail->Subject = "Confirmation Email";
$mail->Body = $body;

$mail->send();

?>