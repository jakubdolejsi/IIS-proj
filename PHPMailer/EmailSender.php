<?php


namespace PHPMailer;


use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


class EmailSender
{
	public function sendEmail(PHPMailer $mailer)
	{
		$mailer->send();
	}

	public function setRecipient(PHPMailer $mailer, $recipientAddress)
	{
		$mailer->addAddress($recipientAddress);
	}

	public function setupReservationEmail(PHPMailer $mailer, $ticketInfo)
	{
		$this->configureSetting($mailer);
		$this->setTicketInfo($mailer, $ticketInfo);
	}

	public function setupReservationEmailRegistered(PHPMailer $mailer, $ticketInfo, $user)
	{
		$this->configureSetting($mailer);
		$this->setTicketInfoRegistered($mailer, $ticketInfo, $user);
	}

	public function setupVerificationEmail(PHPMailer $mailer, $verificationCode, $userId): void
	{
		$this->configureSetting($mailer);
		$this->setCode($mailer, $verificationCode, $userId);
	}

	/**
	 * @param $mailer
	 * @return mixed
	 */
	private function configureSetting(PHPMailer $mailer): void
	{
		try {
			$mailer->isSMTP();
			$mailer->SMTPOptions = [
				'ssl' => [
					'verify_peer'       => FALSE,
					'verify_peer_name'  => FALSE,
					'allow_self_signed' => TRUE,
				],
			];
			$mailer->Host = 'smtp.gmail.com';
			$mailer->SMTPAuth = TRUE;
			$mailer->Username = 'iis.test587@gmail.com';
			$mailer->Password = 'Testheslo123';
			$mailer->Port = 587;
			$mailer->CharSet = 'UTF-8';
			$mailer->Encoding = 'base64';

			$mailer->setFrom('iis.test587@gmail.com');
		}
		catch (Exception $e) {
			echo "Nepodařilo se odeslat email s ověřovacím kódem. Chyba: {$mailer->ErrorInfo}";
		}
	}

	/**
	 * @param PHPMailer $mailer
	 * @param           $code
	 * @param           $userId
	 */
	private function setCode(PHPMailer $mailer, $code, $userId): void
	{
		if (isset($_SERVER['HTTPS'])) {
			$https = 'https://';
		} else {
			$https = 'http://';
		}
		$link = "$https" . "$_SERVER[HTTP_HOST]/" . "emailVerification?" . "id=$userId&" . "hash=$code";
		$mailer->isHTML(TRUE);
		$mailer->Subject = 'Ověření registrace';
		$mailer->Body = "<h1>Verifikační kód</h1> Tento kód prosím zadejte pro dokončení registrace: <b>$code</b><br><br>Ověření lze provést i kliknutím na následující odkaz: $link";
		$mailer->AltBody = "Ověřovací kod: $code, $link";
	}

	private function setTicketInfo(PHPMailer $mailer, $ticketInfo)
	{
		$message = "<h1>Potvrzení Vaší rezervace</h1><h3>Informace o rezervaci:</h3><b>Číslo rezervace: $ticketInfo[id]</b><br><br><b>Jméno:</b> $_POST[firstName] <b>Příjmení:</b> $_POST[lastName]<br> <b>Název události:</b> $ticketInfo[name]<br> <b>Začátek události:</b> $ticketInfo[begin] <b>Datum:</b> $ticketInfo[date]
        <br><b>Cena:</b> $ticketInfo[price] <b>Způsob platby:</b> $ticketInfo[payment_type]<br><b>Sedadlo:</b> $ticketInfo[seat] <b>Sál:</b> $ticketInfo[label]";

		$mailer->isHTML(TRUE);
		$mailer->Subject = "Potvrzení rezervace $ticketInfo[name]";
		$mailer->Body = $message;
		$mailer->AltBody = "Potvrzení Vaší rezervace\nInformace o rezervaci:\nJméno: $_POST[firstName] Příjmení: $_POST[lastName]\nNázev události: $ticketInfo[name]\nZačátek události: $ticketInfo[begin] Datum: $ticketInfo[date]
        \nCena: $ticketInfo[price] Způsob platby: $ticketInfo[payment_type]\nSedadlo: $ticketInfo[seat] Sál: $ticketInfo[label]";
	}

	private function setTicketInfoRegistered(PHPMailer $mailer, $ticketInfo, $userInfo)
	{
		$firstName = $userInfo->getFirstName();
		$lastName = $userInfo->getLastName();
		$message = "<h1>Potvrzení Vaší rezervace</h1><h3>Informace o rezervaci:</h3><b>Číslo rezervace: $ticketInfo[id]</b><br><br><b>Jméno:</b> $firstName <b>Příjmení:</b> $lastName<br> <b>Název události:</b> $ticketInfo[name]<br> <b>Začátek události:</b> $ticketInfo[begin] <b>Datum:</b> $ticketInfo[date]
        <br><b>Cena:</b> $ticketInfo[price]<b> Způsob platby:</b> $ticketInfo[payment_type] <br><b>Sedadlo:</b> $ticketInfo[seat] <b>Sál:</b> $ticketInfo[label]";

		$mailer->isHTML(TRUE);
		$mailer->Subject = "Potvrzení rezervace $ticketInfo[name]";
		$mailer->Body = $message;
		$mailer->AltBody = "Potvrzení Vaší rezervace\nInformace o rezervaci:\nJméno: $firstName Příjmení: $lastName\nNázev události: $ticketInfo[name]\nZačátek události: $ticketInfo[begin] Datum: $ticketInfo[date]
        \nCena: $ticketInfo[price] Způsob platby: $ticketInfo[payment_type]\nSedadlo: $ticketInfo[seat] Sál: $ticketInfo[label]";
	}
}