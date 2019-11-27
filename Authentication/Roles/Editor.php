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
			throw new UpdateException('Creating was not successfully completed!');
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
			throw new UpdateException('Updating was not successfully completed!');
		}

		return $returnData;
	}

	public function removeHallbyId($id)
	{
		$query = 'DELETE FROM theatre.hall where hall.id = ?;';
		$res = $this->db->run($query, $id[1]);
		if ($res === '00000') {
			throw new UpdateException('Removing was not successfully completed!');
		}
		//		throw new UpdateSuccess('Hall was successfully removed');
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
			throw new UpdateException('Updating was not successfully completed!');
		}

		return $returnData;
	}

	public function removeEventsByID($id)
	{
		$query = 'DELETE FROM theatre.culture_event where culture_event.id = ?;';
		$res = $this->db->run($query, $id[1]);
		if ($res === '00000') {
			throw new UpdateException('Removing was not successfully completed!');
		}
		//		throw new UpdateSuccess('Hall was successfully removed');
	}


}