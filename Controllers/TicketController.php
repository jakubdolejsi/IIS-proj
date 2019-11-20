<?php


namespace Controllers;


class TicketController extends BaseController
{
	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$user = $this->getModelFactory()->createUserModel();
		if (!$user->isLogged()) {
			$this->alert('Permission denied');
			$this->redirect('login');
		}
		$email = $user->getUserInfo()->getEmail();
		$ticketManager = $this->getModelFactory()->createTicketManager();

		$ticket = $ticketManager->getTicketByEmail($email);
		$this->data['ticket'] = $ticket;
		$this->loadView('ticket');
	}
}