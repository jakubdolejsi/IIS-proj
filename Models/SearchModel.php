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
		$query = 'select ce.name, ce.type, ce.date_from, ce.date_to from theatre.culture_event as ce';

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
		$query = 'select * from theatre.culture_event as ce where ';

		foreach ($data as $key => $value) {
			if (!empty($value)) {
				$query .= "ce.$key = ? and";
			} else {
				unset($data[ $key ]);
			}
		}
		$query = substr_replace($query, '', -3);

		return $this->db->run($query, array_values($data))->fetchAll(PDO::FETCH_ASSOC);
	}
}