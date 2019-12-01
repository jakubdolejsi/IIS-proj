<?php


namespace Authentication\Roles;


use Exceptions\UpdateException;
use PDO;


class Editor extends Cashier
{

	public function addHall($data)
	{
		unset($data['submit']);
		$datas = array_values($data);

		$query = 'insert into theatre.hall (label, seat_schema, capacity, row_count, column_count)
				VALUES (?,?,?,?,?)';
		$res = $this->db->run($query, $datas);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Vytváření se nezdařilo!');
		}
	}

	public function editHallbyId($data, $id)
	{
		unset($data['submit']);
		$returnData = $data;
		$data = array_values($data);
		$data[] = $id[1];

		$query = 'update theatre.hall  set hall.label = ?, hall.seat_schema = ?, hall.capacity = ?, hall.row_count = ?, hall.column_count = ?
				where hall.id = ?';
		$res = $this->db->run($query, $data);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Úprava se nezdařila!');
		}

		return $returnData;
	}

	public function removeHallbyId($id)
	{
		$query = 'DELETE FROM theatre.hall where hall.id = ?;';
		$res = $this->db->run($query, $id[1]);
		if ($res === '00000') {
			throw new UpdateException('Odebírání se nezdařilo!');
		}
		//		throw new UpdateSuccess('Hall was successfully removed');s
	}

	public function getHallById($id)
	{
		$query = 'select * from theatre.hall as h where h.id = ?';

		return $this->db->run($query, $id[1])->fetch(PDO::FETCH_ASSOC);
	}

	public function getAllHalls(): array
	{
		$query = 'select h.id, h.label, h.seat_schema, h.capacity, h.column_count, h.row_count from theatre.hall as h';

		return $this->db->run($query)->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getEventById($id)
	{
		$query = 'select * from theatre.culture_event as ce where ce.id = ?';

		return $this->db->run($query, array_values($id))->fetch(PDO::FETCH_ASSOC);
	}

	public function getAllEvents(): array
	{
		$query = 'select * from theatre.culture_event as ce';

		return $this->db->run($query)->fetchAll(PDO::FETCH_ASSOC);
	}


	public function editEventByID($data, $id)
	{
		unset($data['submit']);
		$returnData = $data;
		$data = array_values($data);
		$data[] = $id[1];

		$query = 'update theatre.culture_event  set culture_event.type = ?, culture_event.date = ?, culture_event.begin = ?,
				culture_event.end = ?, culture_event.price = ? where culture_event.id = ?';
		$res = $this->db->run($query, $data);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Úprava se nezdařila!');
		}

		return $returnData;
	}

	public function removeEventByID($id)
	{
		$deteleTicketsQuery = 'delete from theatre.ticket where id_culture_event = ?';
		$res = $this->db->run($deteleTicketsQuery, $id[1]);
		if($res->errorCode() !== '00000') {
			throw new UpdateException('Odebírání se nezdařilo!');
		}

		$query = 'delete from theatre.culture_event where culture_event.id = ?';
		$res = $this->db->run($query, $id[1]);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Odebírání se nezdařilo!');
		}
		//		throw new UpdateSuccess('Hall was successfully removed');
	}

	public function getAllWorks()
	{
		$query = 'select * from theatre.culture_work as cw';

		return $this->db->run($query)->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getWorksById($id)
	{
		$query = 'select * from theatre.culture_work where culture_work.id = ?';

		return $this->db->run($query, array_values($id))->fetch(PDO::FETCH_ASSOC);
	}

	public function addWork($data)
	{
		unset($data['submit']);
		$data = array_values($data);
		if (count($data) === 6) {
			$data[] = '';
		} else {
			$data[] = $this->uploadImage();
		}
		$query = 'insert into theatre.culture_work (culture_work.name, culture_work.type, culture_work.genre, culture_work.actors, culture_work.ranking, culture_work.description, image)
				VALUES (?,?,?,?,?,?,?)';
		$res = $this->db->run($query, $data);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Vytváření se nezdařilo!');
		}
	}

	public function editWorkById($data, $id)
	{
		unset($data['submit']);
		$returnData = $data;
		$data['image'] = empty($_FILES['image']['name']) ? '' : $this->uploadImage();

		$data = array_values($data);
		$data[] = $id[1];

		$query = 'update theatre.culture_work set culture_work.name = ?, culture_work.type = ?, culture_work.genre = ?,
    			culture_work.actors = ?, culture_work.ranking = ?, culture_work.description = ?, culture_work.image = ?
    			where culture_work.id = ?';

		$res = $this->db->run($query, $data);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Úprava se nezdařila!!');
		}

		return $returnData;
	}

	public function removeWorksByID($id)
	{

	}

	public function uploadImage()
	{
		$target_dir = 'Views' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
		$target_file = getcwd() . DIRECTORY_SEPARATOR . $target_dir . basename($_FILES['image']['name']);
		$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
		$pathToDisplay = $target_file . basename($_FILES['image']['name']);

		$check = getimagesize($_FILES['image']['tmp_name']);
		if ($check !== FALSE) {
			echo 'File is an image - ' . $check['mime'] . '.';
			$uploadOk = 1;
			move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
		}

		return $target_dir . basename($_FILES['image']['name']);
	}

	public function addEvent($data)
	{
		unset($data['submit']);
		$datas = array_values($data);

		$query = 'insert into theatre.culture_event (id, id_hall, id_culture_work, type, date, begin, end, price) 
				VALUES (?,?,?,?,?,?,?,?)';
		$res = $this->db->run($query, $datas);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Vytváření se nezdařilo!');
		}
	}
}