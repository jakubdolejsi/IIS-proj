<?php


namespace PHPMailer;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


class emailSender
{
    public function setupVerificationEmail(PHPMailer $mailer, $verificationCode): void{
        $this->configureSetting($mailer);
        $this->setCode($mailer, $verificationCode);
    }

    public function setupReservationEmail(PHPMailer $mailer, $ticketInfo)
    {
        $this->configureSetting($mailer);
        $this->setTicketInfo($mailer, $ticketInfo);
    }

    public function setRecipient(PHPMailer $mailer, $recipientAddress)
    {
        $mailer->addAddress($recipientAddress);
    }

    public function sendEmail(PHPMailer $mailer){
        $mailer->send();
    }

    private function setTicketInfo(PHPMailer $mailer, $ticketInfo){
        $message = "<h1>Potvrzení Vaší rezervace</h1><br><h3>Informace o rezervaci:</h3> <b>Jméno:</b> $_POST[firstName] <b>Příjmení:</b> $_POST[lastName]<br> <b>Název události:</b> $ticketInfo[name]<br> <b>Začátek události:</b> $ticketInfo[begin] <b>Datum:</b> $ticketInfo[date]
        <br><b>Cena:</b> $ticketInfo[price] <br><b>Sedadlo:</b> $ticketInfo[seat] <b>Sál:</b> $ticketInfo[label]";

        $mailer->isHTML(true);
        $mailer->Subject = "Potvrzení rezervace $ticketInfo[name]";
        $mailer->Body = $message;
        $mailer->AltBody = "Potvrzení Vaší rezervace\nInformace o rezervaci:\nJméno: $_POST[firstName] Příjmení: $_POST[lastName]\nNázev události: $ticketInfo[name]\nZačátek události: $ticketInfo[begin] Datum: $ticketInfo[date]
        \nCena: $ticketInfo[price]\nSedadlo: $ticketInfo[seat] Sál: $ticketInfo[label]";
    }

    /**
     * @param $mailer
     * @return mixed
     */
    private function configureSetting(PHPMailer $mailer): void
    {
        try{
            $mailer->isSMTP();
            $mailer->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mailer->Host       = 'smtp.gmail.com';
            $mailer->SMTPAuth   = true;
            $mailer->Username   = 'iis.test587@gmail.com';
            $mailer->Password   = 'Testheslo123';
            $mailer->Port       = 587;

            $mailer->setFrom('iis.test587@gmail.com');
        } catch (Exception $e) {
            echo "Nepodařilo se odeslat email s ověřovacím kódem. Chyba: {$mailer->ErrorInfo}";
        }
    }

    /**
     * @param $mailer
     * @param $code
     */
    private function setCode(PHPMailer $mailer, $code): void
    {
        $mailer->isHTML(true);
        $mailer->Subject = 'Ověření registrace';
        $mailer->Body = "<h1>Verifikační kód</h1> Tento kód prosím zadejte pro dokončení registrace: <b>$code</b>";
        $mailer->AltBody = "Ověřovací kod: $code";
    }
}