<?php


namespace Controllers;


use Exceptions\AlreadyOccupiedSeatException;
use Exceptions\InvalidRequestException;
use Exceptions\ReservationSuccessException;
use Exceptions\SqlSomethingGoneWrongException;
use PHPMailer\EmailSender;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


class ReservationController extends BaseController
{


	public function actionCreate($params)
	{
		$user = $this->getModelFactory()->createUserModel();
		if ($user->isLogged()) {
			$this->loadView('reservation');

			try {
				$user->createReservation($params);
			}
			catch (InvalidRequestException $e) {
				$this->alert($e->getMessage());
				$this->redirect('search');
			}
			catch (AlreadyOccupiedSeatException $e) {
				$this->alert($e->getMessage());
			}
			catch (SqlSomethingGoneWrongException $e) {
				$this->alert($e->getMessage());
			}
			catch (ReservationSuccessException $e) {
				$this->alert($e->getMessage());
				$this->redirect('ticket');
			}
		}else{
			$this->loadView('reservationUnregistered');

			$registeredOK = $user->oneTimeRegister();
			if ($registeredOK) {
				$user = $this->getModelFactory()->createUserModel();

				try {
					$ticketId = $user->createReservation($params);
					$ticket = $this->getModelFactory()->createTicketManager()->getTicketById($ticketId);

					$mail = new PHPMailer(true);
					$settings = new EmailSender;
					$user =  $user->getRole()->getNotRegisteredUserByEmail($_POST['email']);
					try{
						$settings->setupReservationEmail($mail, $ticket);
						$settings->setRecipient($mail, $user->getEmail());
						$settings->sendEmail($mail);
					} catch (Exception $e) {
						echo "Nepodarilo se odeslat verifikacni email. Error: {$mail->ErrorInfo}";
					}
					$this->alert("Na vas email byly odeslany informace o rezervaci");
					$this->redirect('home');
				}
				catch (InvalidRequestException $e) {
					$this->alert($e->getMessage());
					$this->redirect('search');
				}
				catch (AlreadyOccupiedSeatException $e) {
					$this->alert($e->getMessage());
				}
				catch (SqlSomethingGoneWrongException $e) {
					$this->alert($e->getMessage());
				}
			}
		}
	}


	public function actionDefault(): void
	{
		// TODO: Implement actionDefault() method.
	}
}