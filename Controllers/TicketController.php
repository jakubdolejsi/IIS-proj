<?php


namespace Controllers;


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
		$this->loadView('ticket');
	}

	public function actionConfirm(){

    }
}