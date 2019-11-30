<?php


namespace Controllers;


use Exceptions\InvalidRequestException;
use Exceptions\PaymentException;

class TicketController extends BaseController
{
	public function actionDefault(): void
	{
		$this->hasPermission('admin', 'editor', 'cashier', 'registeredUser');

		$user = $this->getModelFactory()->createUserModel();

		$email = $user->getUserInfo()->getEmail();
		$ticketManager = $this->getModelFactory()->createTicketManager();

		$ticket = $ticketManager->getTicketByEmail($email);
		$this->data['ticket'] = $ticket;
		$this->data['currentTime'] = date('i-s');
		$this->data['todayDate'] = date('Y-m-s');
		$this->loadView('ticket');
	}

	//TODO uhradu i storno pravdepodobne muze proves i clovek, kteremu nepatri listek (pokud by vlozil do URL spravne ID)
	public function actionConfirm($params){
        $this->hasPermission('admin', 'editor', 'cashier', 'registeredUser');
        $ticket = $this->getModelFactory()->createTicketManager();
        if($ticket->checkURL($params)){
            try{
                $price = $ticket->getPrice($params);
            }catch (InvalidRequestException $exception){
                $this->alert($exception->getMessage());
                $this->redirect('error');
            }
            $this->data['price'] = $price;
            $this->loadView('ticketConfirm');
            try{
                if($ticket->confirmPayment($params, $price)){
                    $this->alert('Částka uhrazena!');
                    $this->redirect('ticket');
                }
            }catch (PaymentException $exception){
                $this->alert($exception->getMessage());
            }
        }else{
            $this->redirect('error');
        }
    }

    public function actionStorno($params){
        $this->hasPermission('admin', 'editor', 'cashier', 'registeredUser');
        $ticket = $this->getModelFactory()->createTicketManager();
        if($ticket->checkURL($params)){
            $ticketData = $ticket->getTicketById($params[0]);
            if($ticketData === false){
                $this->alert('Zadaná rezervace neexistuje!');
                $this->redirect('error');
            }else {
                $ticket->stornoReservation($params[0]);
            }
            $this->alert('Rezervace stornována!');
            $this->redirect('ticket');
        }else{
            $this->redirect('error');
        }
    }
}