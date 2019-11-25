<?php


namespace Controllers;


use Exceptions\DuplicateUser;
use Exceptions\PasswordsAreNotSameException;


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
            $this->redirect('emailVerification');
        }

		if (isset($registeredOK)) {
		    if($registeredOK){
                $this->redirect('home');
            }
		}
	}
}