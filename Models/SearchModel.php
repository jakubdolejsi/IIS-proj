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

    public function getAllFutureEvents(): array
    {
        $date = date('Y-m-d');
        $query = 'select cw.name, cw.type, cw.genre, ce.date, ce.begin, h.label, ce.price from theatre.culture_event as ce 
				join theatre.culture_work as cw on ce.id_culture_work = cw.id
				join theatre.hall as h on ce.id_hall = h.id where ce.date >= ? order by ce.date asc, ce.begin asc';

        return $this->db->run($query, $date)->fetchAll(PDO::FETCH_ASSOC);
    }

	/**
	 * @return array
	 */
	public function process(): array
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = $this->auth->role()->loadPOST();
			if ($this->arrayEmpty($data)) {
				return $this->getAllFutureEvents();
			}

			return $this->getConcreteEvents($data);
		}

		return $this->getAllFutureEvents();
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
			if((!empty($value) && $key ==='begin')){
                $query .= " ce.$key >= ? and";
            }else if(!empty($value) && $key === 'name'){
                $query .= " cw.$key >= ? and";
            }else if (!empty($value)) {
                $query .= " ce.$key = ? and";
			} else {
				unset($data[ $key ]);
			}
		}
		$query = substr_replace($query, '', -3) . ' order by ce.date asc, ce.begin asc';

		return $this->db->run($query, array_values($data))->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCultureWorkByName($name)
	{
		$name = array_values(str_replace('%20', ' ', $name));
		$query = 'select cw.name, cw.type, cw.genre, cw.actors, cw.ranking, cw.description, cw.image from theatre.culture_work as cw where cw.name = ?';

		return $this->db->run($query, $name)->fetch(PDO::FETCH_ASSOC);
	}
}