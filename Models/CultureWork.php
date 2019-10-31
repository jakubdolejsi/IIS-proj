<?php


namespace Models;


use PDO;


class CultureWork extends BaseModel
{
	public function getCultureWorkByEvent($eventName): array
	{
		$eventName = str_replace('%20', ' ', $eventName);
		$query = 'select cw.name, cw.type, cw.actors, cw.genre, cw.ranking from theatre.culture_work as cw join theatre.culture_event as ce on cw.id = ce.id_culture_work
				where ce.name = ?';

		return $this->db->run($query, $eventName)->fetchAll(PDO::FETCH_ASSOC);
	}

}