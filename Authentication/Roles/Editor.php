<?php


namespace Authentication\Roles;


use Exceptions\UpdateException;
use Exceptions\UpdateSuccess;
use PDO;


class Editor extends Cashier
{

	public function addHall($data)
	{
		$datas = array_values($data);

		$query = 'insert into theatre.hall (label, seat_schema, capacity, row_count, column_count)
				VALUES (?,?,?,?,?)';
		$res = $this->db->run($query, $datas);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Creating was not successfully completed!');
		}
		throw new UpdateSuccess('Hall was successfully created');
	}

	public function editHallbyId($data, $id)
	{
		$datas = array_values($data);
		$datas[] = $id;

		$query = 'update theatre.hall as h set h.label = ?, h.seat_schema = ?, h.capacity = ?, h.row_count = ?, h.column_count = ?
				where h.id = ?';
		$res = $this->db->run($query, $datas);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Updating was not successfully completed!');
		}
		throw new UpdateSuccess('Hall was successfully edited');
	}

	public function removeHallbyId($id)
	{
		$query = 'DELETE FROM theatre.hall where hall.id = ?;';
		$res = $this->db->run($query, $id);
		if ($res === '00000') {
			throw new UpdateException('Removing was not successfully completed!');
		}
		throw new UpdateSuccess('Hall was successfully removed');
	}

	public function getHallById($id)
	{
		$query = 'select * from theatre.hall as h where h.id = ?';

		return $this->db->run($query, $id)->fetch(PDO::FETCH_ASSOC);
	}

}