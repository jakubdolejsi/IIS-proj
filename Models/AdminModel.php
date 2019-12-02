<?php


namespace Models;


use PDO;


class AdminModel extends BaseModel
{
	/**
	 * @param $id
	 */
	public function deleteUserByID($id): void
	{
		$deleteTicketsQuery = 'delete from theatre.ticket where id_user = ?';
		$this->db->run($deleteTicketsQuery, $id);

		$deleteUserQuery = 'delete from theatre.user where id = ?';
		$this->db->run($deleteUserQuery, $id);
	}

	public function getAllUsers()
	{
		$query = 'select u.id, u.firstName, u.lastName, u.email, u.role from theatre.user as u';

		return $this->db->run($query)->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @return array
	 */
	public function process(): array
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = $this->auth->role()->loadPOST();
			if ($this->arrayEmpty($data)) {
				return $this->getAllUsers();
			}

			return $this->getConcreteUsers($data);
		}

		return $this->getAllUsers();
	}

	public function processEdit($id)
	{
		$role = $this->auth->role()->getRoleFromSession();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = $this->loadPOST();
			$role->adminEditUser($data, $id);
		}

		// zobraz daneho uzivatele dle id
		return $role->getUserByID($id);
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
	private function getConcreteUsers($data): array
	{
		$query = 'select u.id, u.firstName, u.lastName, u.email, u.role from theatre.user as u 
				where ';

		foreach ($data as $key => $value) {
			if (!empty($value)) {
				$query .= " u.$key = ? and";
			} else {
				unset($data[ $key ]);
			}
		}
		$query = substr_replace($query, '', -3) . ' order by u.lastName asc';

		return $this->db->run($query, array_values($data))->fetchAll(PDO::FETCH_ASSOC);
	}

}