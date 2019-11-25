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

    }
}