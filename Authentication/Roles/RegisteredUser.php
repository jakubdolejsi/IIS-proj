<?php


namespace Authentication\Roles;


use Authentication\Password;
use Database\Db;
use Exceptions\{AlreadyOccupiedSeatException,
	DuplicateUser,
	InvalidPasswordException,
	InvalidRequestException,
	NoUserException,
	PasswordsAreNotSameException,
	ReservationSuccessException,
	SqlSomethingGoneWrongException,
	UpdateProfileException,
	UpdateProfileSuccess};
use Models\UserDetail;
use PDO;


class RegisteredUser extends NotRegisteredUser
{
	/**
	 *
	 */
	public function logout(): void
	{
		unset($_SESSION['user_id']);
        unset($_SESSION['role']);
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

        // TODO fixne dana cena a sleva
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
	 * @throws UpdateProfileException
	 * @throws UpdateProfileSuccess
	 */
	public function editProfile(): void
	{
		$newEmail = $this->getPostDataAndValidate()['email'];
		$actualEmail = $this->getUserBySessionID()->getEmail();
		$query = 'update theatre.user set email = ? where email = ?';
		$res = $this->db->run($query, [$newEmail, $actualEmail]);
		if ($res->errorCode() !== '00000') {
			throw new UpdateProfileException('Updating profile was not successfully completed!');
		}
		throw new UpdateProfileSuccess('Your email was successfully updated to ' . $newEmail);
	}

	/**
	 * @throws PasswordsAreNotSameException
	 * @throws UpdateProfileException
	 * @throws UpdateProfileSuccess
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
	 * @throws UpdateProfileException
	 * @throws UpdateProfileSuccess
	 */
	private function updatePassword(UserDetail $userDetail): void
	{
		$query = 'update theatre.user set password = ? where email = ?';
		$password = $userDetail->getPassword();
		$email = $this->getUserBySessionID()->getEmail();

		$res = $this->db->run($query, [$password, $email]);
		if ($res->errorCode() !== '00000') {
			throw new UpdateProfileException('Updating profile was not successfully completed!');
		}
		throw new UpdateProfileSuccess('Your password was successfully updated');
	}

}