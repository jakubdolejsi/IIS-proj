<?php


namespace Controllers;


use Exceptions\CompleteRegistrationException;
use Exceptions\DuplicateUser;
use Exceptions\NoUserException;
use Exceptions\PasswordsAreNotSameException;
use PHPMailer\EmailSender;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


class RegistrationController extends BaseController
{

	public function actionDefault(): void
	{
		$this->loadView('registration');
		$mail = new PHPMailer(TRUE);
		$settings = new EmailSender;
		$registrationModel = $this->getModelFactory()->createUserModel();
		try {
			$registeredOK = $registrationModel->register();         //Bezna registrace
		}
		catch (PasswordsAreNotSameException $e) {
			$this->alert($e->getMessage());
		}
		catch (DuplicateUser $e) {
			$this->alert($e->getMessage());
		}
		catch (CompleteRegistrationException $e) {
			$registeredOK = $registrationModel->getRole()->completeRegistration();      //Dokonceni registrace
		}
		//Jakakoliv z moznych rezervaci probehla uspesne
		if (isset($registeredOK)) {
			if ($registeredOK) {
				$recipient = $registrationModel->getRole()->getNotRegisteredUserByEmail();
				$hashCode = $registrationModel->getHashCode();
				try {
					$registrationModel->getRole()->insertHash($hashCode);
				}
				catch (NoUserException $e) {
					$this->alert($e->getMessage());
				}
				try {
					$settings->setupVerificationEmail($mail, $hashCode);
					$settings->setRecipient($mail, $recipient->getEmail());
					$settings->sendEmail($mail);
				}
				catch (Exception $e) {
					$this->alert("Nepodařilo se odeslat ověřovací email. Chyba: {$mail->ErrorInfo}");
					$this->redirect('registration');
				}
				$_SESSION['recipient'] = $recipient->getEmail();
				$this->alert("Na váš email byl odeslán ověřovací kód!");
				$this->redirect('emailVerification');
			}
		}
	}
}