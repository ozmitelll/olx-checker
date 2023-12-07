<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require (__DIR__ . '/../vendor/autoload.php');

class MailSender{
function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        $mail->setFrom('ozmitel08@gmail.com', 'OLX Checker');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ozmitel08@gmail.com';
        $mail->Password = 'sali gwcd wgfe spdp';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->send();
        echo 'Email sent successfully';
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

}