<?php


namespace Authentication\Roles;


use Authentication\Password;
use Database\Db;
use Exceptions\AlreadyOccupiedSeatException;
use Exceptions\CompleteRegistrationException;
use Exceptions\DuplicateUser;
use Exceptions\InvalidPasswordException;
use Exceptions\InvalidRequestException;
use Exceptions\NoUserException;
use Exceptions\PasswordsAreNotSameException;
use Exceptions\SqlSomethingGoneWrongException;
use Exceptions\UserNotVerifiedException;
use Models\UserDetail;
use PDO;


class NotRegisteredUser extends Password{

    protected $db;


    public function __construct(Db $db)
    {
        $this->db = $db;
    }


    public function generateHash():string
    {
	    $seed = random_int(0, 10000);

	    return hash('md5', $seed);
    }

    /**
     * @param $email
     * @return mixed|string
     */
    protected function getUserByEmail($email)
    {
        $query = 'select * from theatre.user where email = ?';
        $res = $this->db->run($query, $email)->fetch(PDO::FETCH_ASSOC);

        return (empty($res) ? '' : $res);
    }


    /**
     * @return UserDetail
     */
    public function getUserBySessionID(): UserDetail
    {
        $id = $_SESSION['user_id'];
        $query = 'select * from theatre.user where id=?';

        return new UserDetail($this->db->run($query, [$id])->fetchAll()[0]);
    }

    /**
     * @return UserDetail
     */
    public function getNotRegisteredUserByEmail(): UserDetail
    {
	    $data = $this->loadPOST();
        $query = 'select * from theatre.user where email=?';
        return new UserDetail($this->db->run($query, [$data['email']])->fetch(PDO::FETCH_ASSOC));
    }

    public function insertHash($hashCode)
    {
	    $userDetail = new UserDetail($this->loadPOST());
        $user = $this->getUserByEmail($userDetail->getEmail());
        if (empty($user)) {
            throw new NoUserException('User does not exists');
        }
        $query = 'UPDATE theatre.user SET user.hash=? where user.email=?';
        $queryParams = [$hashCode, $user['email']];
        $this->db->run($query, $queryParams);
    }


    public function verifyHash()
    {
        if(isset($_GET['user'])){
	        $id = $this->loadGET()['id'];
        }else{
            $id = $_SESSION['user'];
        }
	    $insertedCode = $this->loadGET()['hash'];
        $query = 'select user.hash from theatre.user where id=? and hash=?';
        $userHash = $this->db->run($query, [$id, $insertedCode])->fetch(PDO::FETCH_ASSOC);

	    return $insertedCode === $userHash['hash'];
    }


    /**
     * @throws InvalidPasswordException
     * @throws NoUserException
     * @throws UserNotVerifiedException
     */
    public function login(): void
    {
	    $userDetail = new UserDetail($this->loadPOST());
        $user = $this->getUserByEmail($userDetail->getEmail());
        if (empty($user)) {
            throw new NoUserException('User does not exists');
        }
        if($user['is_verified'] === '0'){
            throw new UserNotVerifiedException('Přihlášení se nezdařilo, účet je nutné ověřit pomocí kódu, který vám přišel na email!');
        }
        $password = $userDetail->getPassword();
        $hash = $user['password'];
        if (!$this->verifyHashPassword($password, $hash)) {
            throw new InvalidPasswordException('Invalid password');
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
    }

    public function oneTimeRegister(): void
    {
	    $userDetail = new UserDetail($this->loadPOST());
        $user = $this->getUserByEmail($userDetail->getEmail());
        if (empty($user)) {    //Neni potreba vkladat zaznam
            $query = 'INSERT INTO theatre.user(email, role) VALUES (?, ?)';
            $userDetail->setRole('notRegisteredUser');
            $queryParams = [$userDetail->getEmail(), $userDetail->getRole()];
            $this->db->run($query, $queryParams);
        }
    }

    /**
     * @throws PasswordsAreNotSameException
     * @throws DuplicateUser
     * @throws CompleteRegistrationException
     */
    public function register(): void
    {

	    $userDetail = new UserDetail($this->loadPOST());
        $user = $this->getUserByEmail($userDetail->getEmail());
        if($user){
	        if ($user['role'] !== 'notRegisteredUser') {        //Uzivatel se jiz registroval
                throw new DuplicateUser('User already exists');
            }
            else {  //Uzivatel se chce doregistrovat
                throw new CompleteRegistrationException();
            }
        }
        $this->processRegistrationPassword($userDetail);
        $query = 'INSERT INTO theatre.user(firstName, lastName, email, password, role) VALUES (?, ?, ?, ?, ?)';
        $this->db->run($query, $userDetail->getAllProperties());
    }


	public function verifiedUser(): void
	{
		$email = $this->loadPOST()['email'];
		$query = 'update theatre.user set is_verified = 1';
		$this->db->run($query, $email);
	}

    public function completeRegistration():bool
    {
	    $userDetail = new UserDetail($this->loadPOST());
        $user = $this->getUserByEmail($userDetail->getEmail());
        $this->processRegistrationPassword($userDetail);

        $query = 'UPDATE theatre.user SET firstName=?, lastName=?, password=? where email=?';
        $queryParams = [$userDetail->getFirstName(), $userDetail->getLastName(), $userDetail->getPassword(), $userDetail->getEmail()];
        $this->db->run($query, $queryParams);
        return TRUE;
    }

    public function completeVerification()
    {
        if(isset($_GET['user'])){
	        $id = $this->loadGET()['id'];
        }else{
            $id = $_SESSION['user'];
        }
        $query = 'UPDATE theatre.user SET role=?, is_verified=? where id=?';
        $queryParams = ['registeredUser', TRUE, $id];
        $this->db->run($query, $queryParams);
    }


    /**
     * @param $userDetail
     * @throws PasswordsAreNotSameException
     */
    protected function processRegistrationPassword(UserDetail $userDetail): void
    {
        if (!$userDetail->compareActualPassword()) {
            throw new PasswordsAreNotSameException('Passwords are not same!');
        }
        $password = $userDetail->getPassword();
        $userDetail
            ->setPassword($this->hashPassword($password))
            ->unsetControlPassword()
            ->setRole('registeredUser');
    }


    /**
     * @param $params
     * @return array
     * @throws InvalidRequestException
     */
    protected function getUrlParams($params): array
    {
        $arr = [];
        $values = ['type', 'name', 'label', 'begin'];
        $i = 0;
        foreach ($params as $key => $value) {
            $arr[ $values[ $i ] ] = str_replace('%20', ' ', $value);
            if (empty($value)) {
                throw new InvalidRequestException('Wrong URL');
            }
            $i++;
        }
        if (count($params) !== 4) {
            throw new InvalidRequestException('Wrong URL');
        }

        return $arr;
    }

    /**
     * @param $seat
     * @return string
     */
    protected function joinSeat($seat): string
    {
        return $seat['column'] . $seat['row'];
    }

    /**
     * @param $urlParams
     * @param $seatInfo
     * @return bool
     */
    protected function isSeatFree($urlParams, $seatInfo): bool
    {
        $existingReservationQuery = 'select * from theatre.ticket as t 
									join theatre.culture_event as ce on t.id_culture_event = ce.id
									join theatre.culture_work as cw on ce.id_culture_work = cw.id
									join theatre.hall as h on ce.id_hall = h.id
									where h.label = ? and ce.begin = ? and ce.type = ? and cw.name = ? and t.seat = ?';

        $queryParams = [$urlParams['label'], $urlParams['begin'], $urlParams['type'], $urlParams['name'], $seatInfo];

        return empty($this->db->run($existingReservationQuery, $queryParams)->fetchAll(PDO::FETCH_ASSOC));
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
	    $seatInfo = $this->joinSeat($this->loadPOST());
        if (!$this->isSeatFree($urlParams, $seatInfo)) {
            throw new AlreadyOccupiedSeatException('Seat is already registered');
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
	    $data = $this->loadPOST();
        $userIdQuery = 'select u.id from theatre.user as u where u.email = ?';
        $userId = $this->db->run($userIdQuery, $data['email'])->fetch(PDO::FETCH_ASSOC)['id'];
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

        return $this->db->lastInsertId();
    }

    public function __toString()
    {
        return __CLASS__;
    }

}