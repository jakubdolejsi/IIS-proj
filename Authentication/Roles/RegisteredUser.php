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
     * @throws AlreadyOccupiedSeatException
     * @throws InvalidRequestException
     * @throws ReservationSuccessException
     * @throws SqlSomethingGoneWrongException
     */
    public function createNewReservation($params): void
    {
        $urlParams = $this->getUrlParams($params);
        $seatInfo = $this->joinSeat($this->getPostDataAndValidate());
        if (!$this->isSeatFree($urlParams, $seatInfo)) {
            throw new AlreadyOccupiedSeatException('Seat is already registered');
        }
        $this->createNewTicket($urlParams, $seatInfo);
    }

    /**
     * @param $urlParams
     * @param $seatInfo
     * @throws ReservationSuccessException
     * @throws SqlSomethingGoneWrongException
     * @throws InvalidRequestException
     */
    private function createNewTicket($urlParams, $seatInfo): void
    {
        $userIdQuery = 'select u.id from theatre.user as u where u.email = ?';
        $userId = $this->db->run($userIdQuery, $this->getUserBySessionID()->getEmail())->fetch(PDO::FETCH_ASSOC)['id'];
        if (!isset($userId)) {
            throw new InvalidRequestException('Wrong URL');
        }

        $cultureEventIdQueryParams = [$urlParams['label'], $urlParams['begin'], $urlParams['type'], $urlParams['name']];
        $cultureEventIdQuery = 'select ce.id, ce.price from theatre.culture_event as ce
							join theatre.culture_work as cw on ce.id_culture_work = cw.id
							join theatre.hall as h on ce.id_hall = h.id
							where h.label = ? and ce.begin = ? and ce.type = ? and cw.name = ?';
        $cultureEventRes = $this->db->run($cultureEventIdQuery, $cultureEventIdQueryParams)->fetch(PDO::FETCH_ASSOC);
        if (!isset($cultureEventRes['id'])) {
            throw new InvalidRequestException('Wrong URL');
        }

        $queryParams = [$userId, $cultureEventRes['id'], $cultureEventRes['price'], $seatInfo, 0];
        $query = 'insert into theatre.ticket (id_user, id_culture_event, price, seat, discount) 
				values (?, ?, ?, ?, ?)';

        $res = $this->db->run($query, $queryParams);
        if ($res->rowCount() === '0') {
            throw new SqlSomethingGoneWrongException('Internal error occured');
        }
        throw new ReservationSuccessException('Reservation was successfully created!');
    }

	/**
	 * @throws UpdateException
	 * @throws UpdateSuccess
	 */
	public function editProfile(): void
	{
		$newEmail = $this->getPostDataAndValidate()['email'];
		$actualEmail = $this->getUserBySessionID()->getEmail();
		$query = 'update theatre.user set email = ? where email = ?';
		$res = $this->db->run($query, [$newEmail, $actualEmail]);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Updating profile was not successfully completed!');
		}
		throw new UpdateSuccess('Your email was successfully updated to ' . $newEmail);
	}

	/**
	 * @throws PasswordsAreNotSameException
	 * @throws UpdateException
	 * @throws UpdateSuccess
	 */
	public function editPassword(): void
	{
		$user = new UserDetail($this->getPostDataAndValidate());
		if (!$user->compareNewPassword()) {
			throw new PasswordsAreNotSameException('Passwords does not match!');
		}
		$query = 'select password from theatre.user where email = ?';
		$oldHash = $this->db->run($query, $this->getUserBySessionID()->getEmail())->fetch(PDO::FETCH_ASSOC);
		if (!$this->verifyHashPassword($user->getPassword(), $oldHash['password'])) {
			throw new PasswordsAreNotSameException('Actual password is not correct!');
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
		$query = 'update theatre.user set password = ? where email = ?';
		$password = $userDetail->getPassword();
		$email = $this->getUserBySessionID()->getEmail();

		$res = $this->db->run($query, [$password, $email]);
		if ($res->errorCode() !== '00000') {
			throw new UpdateException('Updating profile was not successfully completed!');
		}
		throw new UpdateSuccess('Your password was successfully updated');
	}

	public function getUserByID($id)
	{
		$query = 'select * from theatre.user as u where u.id = ?';

		return $this->db->run($query, $id)->fetch(PDO::FETCH_ASSOC);
	}

	public function adminEditUser($dataToUpdate, $id)
	{
		unset($dataToUpdate['submit']);
		$dataToUpdate['id'] = (int)$id[0];
		$dataToUpdate['is_verified'] = (int)$dataToUpdate['is_verified'];
		$dataToUpdate = array_values($dataToUpdate);
		$query = 'update theatre.user set user.firstName = ?, user.lastName = ?, user.email = ?, user.role = ?, user.is_verified = ? where user.id = ?';

		$this->db->run($query, $dataToUpdate);
	}

}