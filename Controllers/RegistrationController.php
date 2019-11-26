<?php


namespace Controllers;


use Exceptions\CompleteRegistrationException;
use Exceptions\DuplicateUser;
use Exceptions\NoUserException;
use Exceptions\PasswordsAreNotSameException;
use PHPMailer\emailSender;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


class RegistrationController extends baseController
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
            $recipient = $registrationModel->getRole()->getNotRegisteredUserByEmail();
            try{
                $hashCode = $registrationModel->getHashCode();
                $settings->setupVerificationEmail($mail, $hashCode);
                $settings->setRecipient($mail, $recipient->getEmail());
                $settings->sendEmail($mail);
                try{
                    $registrationModel->getRole()->insertHash($hashCode);
                }
                catch (NoUserException $e){
                    $this->alert($e->getMessage());
                }
                $_SESSION['password'] = $registrationModel->getRole()->getRegisterPassword();
                $_SESSION['recipient'] = $recipient->getEmail();
                $this->alert($e->getMessage());
                $this->redirect('emailVerification');
            } catch (Exception $e) {
                echo "Nepodarilo se odeslat verifikacni email. Error: {$mail->ErrorInfo}";
            }
        }

		if (isset($registeredOK)) {
		    if($registeredOK){
                $this->redirect('home');
            }
		}
	}
}