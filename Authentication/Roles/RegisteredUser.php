<?php


namespace Authentication\Roles;


use Exceptions\{AlreadyOccupiedSeatException,
	InvalidRequestException,
	PasswordsAreNotSameException,
	ReservationSuccessException,
	SqlSomethingGoneWrongException,
	UpdateException,
	UpdateSuccess};
use Models\UserDetail;
use PDO;


class RegisteredUser extends NotRegisteredUser
{
	/**
	 *
	 */
	public function logout(): void
	{
		unset($_SESSION['user_id'], $_SESSION['role']);
		session_destroy();
	}

    /**
     * @param $params
     * @return string
     * @throws AlreadyOccupiedSeatException
     * @throws InvalidRequestException
     * @throws SqlSomethingGoneWrongException
     */
    public function createNewReservation($params)
    {
        $urlParams = $this->getUrlParams($params);
	    $seatInfo = $this->loadPOST();
        if (!$this->isSeatFree($urlParams, $seatInfo)) {
            throw new AlreadyOccupiedSeatException('Sedadlo je již obsazeno!');
        }
        return $this->createNewTicket($urlParams, $seatInfo);
    }

    /**
     * @param $urlParams
     * @param $seatInfo
     * @return string
     * @throws InvalidRequestException
     * @throws SqlSomethingGoneWrongException
     */
    private function createNewTicket($urlParams, $seatInfo)
    {
        $payment = $this->loadPOST()['type'];
        $userIdQuery = 'select u.id from xsvera04.user as u where u.email = ?';
        $userId = $this->db->run($userIdQuery, $this->getUserBySessionID()->getEmail())->fetch(PDO::FETCH_ASSOC)['id'];
        if (!isset($userId)) {
            throw new InvalidRequestException('Neplatná URL adresa!');
        }

        $cultureEventIdQueryParams = [urldecode($urlParams['label']), urldecode($urlParams['begin']), urldecode($urlParams['type']), urldecode($urlParams['id'])];
        $cultureEventIdQuery = 'select ce.id, ce.price from xsvera04.culture_event as ce
							join xsvera04.culture_work as cw on ce.id_culture_work = cw.id
							join xsvera04.hall as h on ce.id_hall = h.id
							where h.label = ? and ce.begin = ? and cw.type = ? and ce.id = ?';

        $cultureEventRes = $this->db->run($cultureEventIdQuery, $cultureEventIdQueryParams)->fetch(PDO::FETCH_ASSOC);
        if (!isset($cultureEventRes['id'])) {
            throw new InvalidRequestException('Neplatná URL adresa!');
        }

        $queryParams = [$userId, $cultureEventRes['id'], $cultureEventRes['price'], $seatInfo['seat'], 0,
	        $payment, 2];
        $query = 'insert into xsvera04.ticket (id_user, id_culture_event, price, seat, discount, payment_type, is_paid) 
				values (?, ?, ?, ?, ?, ?, ?)';

        $res = $this->db->run($query, $queryParams);
        if ($res->rowCount() === '0') {
            throw new SqlSomethingGoneWrongException('Interní chyba!');
        }
        return $this->db->lastInsertId();
    }

	/**
	 * @throws UpdateException
	 * @throws UpdateSuccess
	 */
	public function editProfile(): void
	{
		$newEmail = $this->loadPOST()['email'];
		$actualEmail = $this->getUserBySessionID()->getEmail();
		$query = 'update xsvera04.user set email = ? where email = ?';
		$res = $this->db->run($query, [$newEmail, $actualEmail]);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Úprava se nezdařila!');
		}
		throw new UpdateSuccess('Váš email byl změněn na: ' . $newEmail);
	}

	/**
	 * @throws PasswordsAreNotSameException
	 * @throws UpdateException
	 * @throws UpdateSuccess
	 */
	public function editPassword(): void
	{
		$user = new UserDetail($this->loadPOST());
		if (!$user->compareNewPassword()) {
			throw new PasswordsAreNotSameException('Hesla se neshodují!');
		}
		$query = 'select password from xsvera04.user where email = ?';
		$oldHash = $this->db->run($query, $this->getUserBySessionID()->getEmail())->fetch(PDO::FETCH_ASSOC);
		if (!$this->verifyHashPassword($user->getPassword(), $oldHash['password'])) {
			throw new PasswordsAreNotSameException('Původní heslo je nesprávné!');
		}
		$hash = $this->hashPassword($user->getNewPassword());
		$user->setPassword($hash);
		$this->updatePassword($user);
	}

	/**
	 * @param UserDetail $userDetail
	 * @throws UpdateException
	 * @throws UpdateSuccess
	 */
	private function updatePassword(UserDetail $userDetail): void
	{
		$query = 'update xsvera04.user set password = ? where email = ?';
		$password = $userDetail->getPassword();
		$email = $this->getUserBySessionID()->getEmail();

		$res = $this->db->run($query, [$password, $email]);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Úprava se nezdařila!');
		}
		throw new UpdateSuccess('Vaše heslo bylo úspěšně změněno!');
	}

	public function getUserByID($id)
	{
		$query = 'select * from xsvera04.user as u where u.id = ?';

		return $this->db->run($query, $id)->fetch(PDO::FETCH_ASSOC);
	}

	public function adminEditUser($dataToUpdate, $id)
	{
		unset($dataToUpdate['submit']);
		$dataToUpdate['id'] = (int)$id[0];
		$dataToUpdate['is_verified'] = (int)$dataToUpdate['is_verified'];
		$dataToUpdate = array_values($dataToUpdate);
		$query = 'update xsvera04.user set user.firstName = ?, user.lastName = ?, user.email = ?, user.role = ?, user.is_verified = ? where user.id = ?';

		$this->db->run($query, $dataToUpdate);
	}



}