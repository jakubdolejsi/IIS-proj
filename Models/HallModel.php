<?php


namespace Models;


use PDO;


class HallModel extends BaseModel
{
	public function getAllHalls(): array
	{
		$query = 'select h.id, h.label, h.seat_schema, h.capacity, h.column_count, h.row_count from theatre.hall as h';

		return $this->db->run($query)->fetchAll(PDO::FETCH_ASSOC);
	}

}