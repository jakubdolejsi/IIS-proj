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
		$userEmail = $this->getModelFactory()->createUserModel()->getUserInfo()->getEmail();
		$ticketManager = $this->getModelFactory()->createTicketManager();

		$ticket = $ticketManager->getTicketByEmail($userEmail);
		$this->data['ticket'] = $ticket;
		$this->view = 'Ticket';
	}
}