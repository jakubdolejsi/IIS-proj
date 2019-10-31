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
		$query = 'select t.price, ce.name, cw.name, h.label, s.seat_column, s.seat_row, ce.name as cen 
				 from theatre.ticket as t join theatre.user on t.id_user = user.id
				 join theatre.culture_event as ce on t.id_culture_event = ce.id
				 join theatre.culture_work  as cw on ce.id_culture_work = cw.id
				 join theatre.hall as h on ce.id = h.id_event
				 join theatre.seat as s on h.id_seat = s.id
				 where user.email = ?
				 order by ce.date_to';

		return $this->db->run($query, $email)->fetchAll(PDO::FETCH_ASSOC);
	}


	public function createNewUserTicket($price, $discount, $type)
	{
		$query = 'insert into theatre.ticket (price, discount, type, id, id_user, id_culture_event) 
				values (?,?,?,?,?,?)';
	}

}