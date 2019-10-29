<?php


namespace Models;


use PDO;


class Hall extends BaseModel
{
	public function getHallByEmail($email): array
	{
		$query = 'select * from theatre.hall as h join theatre.culture_event as ce on h.id_event = ce.id
				join theatre.ticket as t on ce.id = t.id_culture_event
				join theatre.user as u on t.id_user = u.id
				where u.email = ?';

		return $this->db->run($query, $email)->fetchAll(PDO::FETCH_ASSOC);
	}

}