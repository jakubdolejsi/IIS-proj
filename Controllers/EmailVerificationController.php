<?php

namespace Controllers;


class EmailVerificationController extends BaseController
{

	public function actionDefault(): void
	{
		$this->loadView('emailVerification');

		//Pokud je to GET request a je v URL je nastaveno id a hash (pristup pres link v emailu), nebo je id v session a v URL je hash
		if ($_SERVER['REQUEST_METHOD'] === 'GET' && ((isset($_GET['id'], $_GET['hash'])) || (isset($_SESSION['user'], $_GET['hash'])))){
			$userModel = $this->getModelFactory()->createUserModel();
			if ($userModel->checkVerificationCode()) {
				$userModel->getRole()->completeVerification();
                if(isset($_SESSION['user'])){
                    unset($_SESSION['user']);
                }
                    $this->alert('Registrace proběhla úspěšně, nyní se můžete přihlásit!');
                    $this->redirect('login');
            } else {
                $this->alert('Zadaný kód není správný!');
            }
		}
	}

}