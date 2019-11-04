<?php


namespace Models;


use PDO;


class SearchModel extends BaseModel
{
	/**
	 * @return array
	 */
	public function getAllEvents(): array
	{
		$query = 'select cw.name, cw.type, cw.genre, ce.date, ce.begin, h.label, ce.price from theatre.culture_event as ce 
				join theatre.culture_work as cw on ce.id_culture_work = cw.id
				join theatre.hall as h on ce.id_hall = h.id';

		return $this->db->run($query)->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @return array
	 */
	public function process(): array
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = $this->auth->role()->getPostDataAndValidate();
			if ($this->arrayEmpty($data)) {
				return $this->getAllEvents();
			}

			return $this->getConcreteEvents($data);
		}

		return $this->getAllEvents();
	}

	private function arrayEmpty($data): bool
	{
		foreach ($data as $key => $value) {
			if (empty($value)) {
				unset($data[ $key ]);
			}
		}

		return empty($data);
	}

	/**
	 * @param $data
	 * @return array
	 */
	private function getConcreteEvents($data): array
	{
		$query = 'select cw.name, cw.type, cw.genre, ce.date, ce.begin, h.label, ce.price from theatre.culture_event as ce 
				join theatre.culture_work as cw on ce.id_culture_work = cw.id
				join theatre.hall as h on ce.id_hall = h.id 
				where ';

		foreach ($data as $key => $value) {
			if (!empty($value)) {
				$query .= " ce.$key = ? and";
			} else {
				unset($data[ $key ]);
			}
		}
		$query = substr_replace($query, '', -3) . ' order by ce.date asc';

		return $this->db->run($query, array_values($data))->fetchAll(PDO::FETCH_ASSOC);
	}
}