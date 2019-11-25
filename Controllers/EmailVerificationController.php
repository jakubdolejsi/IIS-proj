<?php


namespace Controllers;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer;
//use PHPMailer\Exception;
//use PHPMailer\SMTP;


class EmailVerificationController extends baseController
{

    /**
     * @param array $params
     * @return mixed
     */
    public function process(array $params): void
    {
        $this->loadView('emailVerification');
        $mail = new PHPMailer(true);
        try{
            $mail->isSMTP();// Send using SMTP
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
//            $mail->SMTPKeepAlive = true;
//            $mail->Mailer = "smtp"; // don't change the quotes!

            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'iis.test587@gmail.com';                     // SMTP username
            $mail->Password   = 'Testheslo123';                               // SMTP password
//            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 587;

            $mail->setFrom('iis.test587@gmail.com');
            $mail->addAddress('tomas.sverak88@gmail.com');

            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Testovaci email';
            $mail->Body    = '<h1>Ahoj!</h1>This is the HTML message body <b>in bold!</b>';

            if($mail->send()){
                echo 'Message has been sent';
            }
        } catch (Exception $e) {
            echo "Nepodarilo se odeslat verifikacni emal. Error: {$mail->ErrorInfo}";
        }
    }
}