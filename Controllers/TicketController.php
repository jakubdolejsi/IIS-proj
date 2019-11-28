<?php


namespace Controllers;


class TicketController extends BaseController
{
	public function actionDefault(): void
	{
		$this->hasPermission('admin', 'editor', 'cashier', 'registeredUser');

		$user = $this->getModelFactory()->createUserModel();
//		if (!$user->isLogged()) {
//			$this->alert('Permission denied');
//			$this->redirect('login');
//		}
		$email = $user->getUserInfo()->getEmail();
		$ticketManager = $this->getModelFactory()->createTicketManager();

		$ticket = $ticketManager->getTicketByEmail($email);
		$this->data['ticket'] = $ticket;
		$this->loadView('ticket');
	}
}