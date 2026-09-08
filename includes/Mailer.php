<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    public static function envoyer($destinataire, $sujet, $messageHtml) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('SMTP_USER');
            $mail->Password = getenv('SMTP_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = getenv('SMTP_PORT');

            $mail->setFrom(getenv('SMTP_FROM'), 'Vite & Gourmand');
            $mail->addAddress($destinataire);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $sujet;
            $mail->Body = $messageHtml;

            return $mail->send();
        } catch (Exception $e) {
            error_log("Erreur envoi mail : " . $mail->ErrorInfo);
            return false;
        }
    }
}