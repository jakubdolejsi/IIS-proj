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
        $message = "<h1>Provedl jste tuto rezervaci</h1> <b>Jmeno:</b> $_POST[firstName] <b>Prijmeni:</b> $_POST[lastName]<br> <b>Nazev udalosti:</b> $ticketInfo[name]<br> <b>Zacatek udalosti:</b> $ticketInfo[begin] <b>Datum:</b> $ticketInfo[date]
        <br><b>Cena:</b> $ticketInfo[price] <br><b>Sedadlo:</b> $ticketInfo[seat] <b>Sal:</b> $ticketInfo[label]";

        $mailer->isHTML(true);
        $mailer->Subject = "Listek na udalost $ticketInfo[name]";
        $mailer->Body = $message;
        $mailer->AltBody = "Provedl jste tuto rezervaci\nNazev udalosti: $ticketInfo[name]\nZacatek udalosti: $ticketInfo[begin]\nDatum: $ticketInfo[date]\n
        Cena: $ticketInfo[price]\nSedadlo: $ticketInfo[seat]\nSal: $ticketInfo[label]";
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
            echo "Nepodarilo se odeslat verifikacni email. Error: {$mailer->ErrorInfo}";
        }
    }

    /**
     * @param $mailer
     * @param $code
     */
    private function setCode(PHPMailer $mailer, $code): void
    {
        $mailer->isHTML(true);
        $mailer->Subject = 'Overeni registrace';
        $mailer->Body = "<h1>Verifikacni kod</h1> Tento kod prosim zadejte pro dokonceni registrace: <b>$code</b>";
        $mailer->AltBody = "Verifikacni kod: $code";
    }
}