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
			$this->loadView('Reservation');

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
			$this->loadView('ReservationUnregistered');

			$registeredOK = $user->oneTimeRegister();
			if ($registeredOK) {
				$user = $this->getModelFactory()->createUserModel();

				try {
					$ticketId = $user->createReservation($params);
					$ticket = $this->getModelFactory()->createTicketManager()->getTicketById($ticketId);

					$mail = new PHPMailer(true);
					$settings = new EmailSender;
					$user =  $user->getRole()->getNotRegisteredUserByEmail();
					try{
						$settings->setupReservationEmail($mail, $ticket);
						$settings->setRecipient($mail, $user->getEmail());
						$settings->sendEmail($mail);
					} catch (Exception $e) {
						echo "Nepodařilo se odeslat lístek na email. Chyba: {$mail->ErrorInfo}";
					}
					$this->alert("Na váš email byly odeslány informace o rezervaci!");
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