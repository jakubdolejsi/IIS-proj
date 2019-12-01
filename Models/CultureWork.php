<?php


namespace Models;


use PDO;


class CultureWork extends BaseModel
{
	public function getCultureWorkByEvent($eventType): array
	{
		$eventType = str_replace('%20', ' ', $eventType);
		$query = 'select * from xsvera04.culture_work as cw 
				join xsvera04.culture_event as ce on cw.id = ce.id_culture_work
				where cw.type = ?';

		return $this->db->run($query, $eventType)->fetchAll(PDO::FETCH_ASSOC);
	}

}