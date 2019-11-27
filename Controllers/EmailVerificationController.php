<?php

namespace Controllers;


class EmailVerificationController extends baseController
{

	public function actionDefault(): void
	{
		$this->loadView('emailVerification');
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['recipient'])) {
			$recipient = $_SESSION['recipient'];
			$userModel = $this->getModelFactory()->createUserModel();
			if ($userModel->checkVerificationCode($recipient)) {
				$userModel->getRole()->completeVerification($_SESSION['recipient']);
				$this->alert('Registrace proběhla úspěšně, nyní se můžete přihlásit!');
				unset($_SESSION['recipient']);
				$this->redirect('login');
			} else {
				$this->alert('Zadaný kód není správný!');
			}
		}
	}

}