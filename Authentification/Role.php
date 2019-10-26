<?php


namespace Authentification;


use Authentification\Roles\Admin;
use Authentification\Roles\Cashier;
use Authentification\Roles\Editor;
use Authentification\Roles\RegisteredUser;
use Database\Db;


class Role extends PostDataValidator
{
	protected $db;

	public function __construct(Db $db)
	{
		$this->db = $db;
	}

	public function getRoleByEmailPOST()
	{
		$data = $this->getPostDataAndValidate();
		$query = ('select usr.role from theatre.users as usr where usr.email = ?');
		$res = $this->db->run($query, $data['email'])->fetchAll();

		return $this->setRole($res[0]['role']);
	}

	public function getRoleBySessionID()
	{
		$sessionID = $_SESSION['user_id'] ?? '';
		if (empty($sessionID)) {
			// nejaka exceptiona, asi ze uzivatel neni prihlaseny..
		}
		$query = 'select usr.role from theatre.users as usr where usr.id = ?';
		$res = $this->db->run($query, $sessionID)->fetchAll();

		return $this->setRole($res[0]['role']);
	}

	private function setRole($role)
	{
		switch ($role) {
			case 'registeredUser':
				return new RegisteredUser($this->db);
			case 'cashier':
				return new Cashier($this->db);
			case 'editor':
				return new Editor($this->db);
			case 'admin':
				return new Admin($this->db);
		}
	}

}