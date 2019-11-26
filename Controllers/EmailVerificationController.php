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
            $password = $_SESSION['password'];

            $userModel = $this->getModelFactory()->createUserModel();
            if($userModel->checkVerificationCode($recipient)){
                $this->alert('Registrace probehla uspesne, nyni se muzete prihlasit');
                $userModel->getRole()->setRoleToRegisterByEmail($recipient);
                try{
                    $userModel->getRole()->setPassword($recipient, $password);
                }
                catch (\Exception $e){

                }
                unset($_SESSION['recipient']);
                unset($_SESSION['password']);
                $this->redirect('login');
            }else{
                $this->alert('Zadany kod neni spravny!');
            }
        }
//        else{
//            $this->alert("Nepodarilo se vygenerovat verifikanci kod, zkuste opakovat registraci!");
//        }
    }
}