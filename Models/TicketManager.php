<?php


namespace Models;



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


	public function createNewUserTicket($price, $discount, $type)
	{
	}


}