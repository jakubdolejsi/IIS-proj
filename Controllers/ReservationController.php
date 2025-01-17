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
				$ticketId = $user->createReservation($params);
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
		} else {
			$this->loadView('reservationUnregistered');

			$registeredOK = $user->oneTimeRegister();
			if ($registeredOK) {
				$user = $this->getModelFactory()->createUserModel();

				try {
					$ticketId = $user->createReservation($params);
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
		if (isset($ticketId)) {
		    $ticketModel = $this->getModelFactory()->createTicketManager();
			$ticket = $ticketModel->getTicketById($ticketId);

			$mail = new PHPMailer(TRUE);
			$settings = new EmailSender;
			if (!$user->isLogged()) {
				$user = $user->getRole()->getNotRegisteredUserByEmail();
				$settings->setupReservationEmail($mail, $ticket);
			} else {
				$user = $user->getUserInfo();
				$settings->setupReservationEmailRegistered($mail, $ticket, $user);
			}
			try {
				$settings->setRecipient($mail, $user->getEmail());
				$settings->sendEmail($mail);
			}
			catch (Exception $e) {
				$this->alert("Nepodařilo se odeslat lístek na email. Chyba: {$mail->ErrorInfo}");
				$ticketModel->stornoReservation($ticketId);
				$this->redirect('search');
			}
			$this->alert("Na váš email byly odeslány informace o rezervaci!");
			$this->redirect('home');
		}
		$data = $user->getReservationInfo($params);
		$this->data['halls'] = $data['hallInfo'];
		$this->data['reservedSeats'] = $data['seatsInfo'];
	}


	public function actionDefault(): void
	{
		$this->redirect('error');
	}
}