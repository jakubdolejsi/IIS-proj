<?php


namespace Authentication;


use Authentication\Roles\Admin;
use Authentication\Roles\Cashier;
use Authentication\Roles\Editor;
use Authentication\Roles\NotRegisteredUser;
use Authentication\Roles\RegisteredUser;
use Database\Db;
use Models\UserDetail;
use PDO;


class Role extends Validator
{
	protected $db;

	public function __construct(Db $db)
	{
		$this->db = $db;
	}

	public function getRoleByEmailPOST()
	{
		$data = new UserDetail($this->getPostDataAndValidate());
		$query = ('select usr.role from theatre.user as usr where usr.email = ?');
		$res = $this->db->run($query, $data->getEmail())->fetch(PDO::FETCH_ASSOC);
		if (empty($res)) {
			return NULL;
		}

		return $this->setRole($res['role']);
	}

	public function getRoleFromeSession()
	{
        $res['role'] = $_SESSION['role'];

		return $this->setRole($res['role']);
	}

	private function setRole($role)
	{
		switch ($role) {
            case 'notRegisteredUser':
                return new NotRegisteredUser($this->db);
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