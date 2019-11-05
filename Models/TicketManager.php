<?php


namespace Models;


use Exceptions\UpdateProfileSuccess;
use PDO;


class TicketManager extends BaseModel
{
	/**
	 * @param $email
	 * @return mixed
	 */
	public function getTicketByEmail($email)
	{
		// todo nejak to vyhazuje dva vysledky
		$query = 'select cw.name, ce.begin, ce.date, t.price, t.seat, h.label from theatre.ticket as t 
				join theatre.user as u on t.id_user = u.id
				join theatre.culture_event as ce on t.id_culture_event = ce.id
				join theatre.culture_work as cw on ce.id_culture_work = cw.id
				join theatre.hall as h on ce.id_hall = h.id
				where u.email = ?';

		return $this->db->run($query, $email)->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getTicket(): array
	{
		if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
			return $this->getAllTickets();
		}
		$data = $this->getPostDataAndValidate();

		if (!isset($data['searchEmail'])) {
			return $this->getAllTickets();
		}
		if (empty($data['searchEmail'])) {
			return $this->getAllTickets();
		}

		$email = $data['searchEmail'];
		$query = 'select t.price, t.seat, t.discount, u.email from theatre.ticket as t 
				join theatre.user as u on t.id_user = u.id
				where u.email = ?';

		return $this->db->run($query, $email)->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @return array
	 */
	private function getAllTickets(): array
	{
		$query = 'select t.price, t.seat, t.discount, u.email from theatre.ticket as t 
				join theatre.user as u on t.id_user = u.id';

		return $this->db->run($query)->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param $data
	 * @return mixed
	 * @throws UpdateProfileSuccess
	 */
	public function processUpdate($data)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dataToQuery = $this->getPostDataAndValidate();
			$query = 'update theatre.ticket as t join theatre.user as u on t.id_user = u.id
					join theatre.culture_event as ce on t.id_culture_event = ce.id
					set t.price =?, t.seat = ?, t.discount = ?
					where u.email = ? and t.id_culture_event = ce.id';

			$res = $this->db->run($query, [$dataToQuery['price'], $dataToQuery['seat'], $dataToQuery['discount'], $dataToQuery['email']]);

			if ($res->errorCode() === '00000') {
				throw new UpdateProfileSuccess('Update was ok');
			}
		}

		return $data;
	}
}