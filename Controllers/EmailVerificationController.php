<?php


namespace Controllers;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\emailSender;


class EmailVerificationController extends baseController
{

    /**
     * @param array $params
     * @return mixed
     */
    public function process(array $params): void
    {
        $this->loadView('emailVerification');
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['recipient'])){
            $recipient = $_SESSION['recipient'];

            $userModel = $this->getModelFactory()->createUserModel();
            if($userModel->checkVerificationCode($recipient)){
                $userModel->getRole()->completeVerification($_SESSION['recipient']);
                $this->alert('Registrace proběhla úspěšně, nyní se můžete přihlásit!');

                unset($_SESSION['recipient']);
                $this->redirect('login');
            }else{
                $this->alert('Zadaný kód není správný!');
            }
        }
//        else{
//            $this->alert("Nepodarilo se vygenerovat verifikanci kod, zkuste opakovat registraci!");
//        }
    }
}