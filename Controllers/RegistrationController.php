<?php


namespace Controllers;


use Exceptions\CompleteRegistrationException;
use Exceptions\DuplicateUser;
use Exceptions\PasswordsAreNotSameException;
use PHPMailer\emailSender;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


class RegistrationController extends BaseController
{

	/**
	 * @param array $params
	 * @return mixed
	 * @throws DuplicateUser
	 * @throws PasswordsAreNotSameException
	 */
	public function process(array $params): void
	{
		$this->loadView('registration');
		$registrationModel = $this->getModelFactory()->createUserModel();
		try{
            $registeredOK = $registrationModel->register();
        }
        catch (PasswordsAreNotSameException $e){
            $this->alert($e->getMessage());
        }
        catch (DuplicateUser $e){
            $this->alert($e->getMessage());
        }
        catch (CompleteRegistrationException $e){
            $mail = new PHPMailer(true);
            $settings = new emailSender();
            try{
                $settings->setupVerificationEmail($mail, $registrationModel->getHashCode());
                $settings->setRecipient($mail, $registrationModel->getUserInfo()->getEmail());
                $settings->sendEmail($mail);
            } catch (Exception $e) {
                echo "Nepodarilo se odeslat verifikacni email. Error: {$mail->ErrorInfo}";
            }
		    $this->alert($e->getMessage());
            $this->redirect('emailVerification');
        }

		if (isset($registeredOK)) {
		    if($registeredOK){
                $this->redirect('home');
            }
		}
	}
}