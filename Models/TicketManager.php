<?php


namespace Models;


use Exceptions\InvalidRequestException;
use Exceptions\PaymentException;
use PDO;


class TicketManager extends BaseModel
{
	/**
	 * @param $email
	 * @return mixed
	 */
	public function getTicketByEmail($email)
	{
	    $date = date('Y-m-d');
		$query = 'select t.id, cw.name, ce.begin, ce.date, t.price, t.seat, h.label, t.payment_type, t.is_paid from theatre.ticket as t 
				join theatre.user as u on t.id_user = u.id
				join theatre.culture_event as ce on t.id_culture_event = ce.id
				join theatre.culture_work as cw on ce.id_culture_work = cw.id
				join theatre.hall as h on ce.id_hall = h.id
				where u.email = ? and ce.date >= ?';

		return $this->db->run($query, [$email, $date])->fetchAll(PDO::FETCH_ASSOC);
	}

    /**
     * @param $ticketId
     * @return mixed
     */
    public function getTicketById($ticketId)
    {
        $query = 'select t.id, cw.name, ce.begin, ce.date, t.price, t.seat, t.payment_type, h.label from theatre.ticket as t 
				join theatre.user as u on t.id_user = u.id
				join theatre.culture_event as ce on t.id_culture_event = ce.id
				join theatre.culture_work as cw on ce.id_culture_work = cw.id
				join theatre.hall as h on ce.id_hall = h.id
				where t.id = ?';

        return $this->db->run($query, $ticketId)->fetch(PDO::FETCH_ASSOC);
    }

    public function getPrice($params){
        $ticket = $this->getTicketById($params[0]);
        if($ticket === false){
            throw new InvalidRequestException('Číslo rezervace neexistuje!');
        }
        return $ticket['price'];
    }


    public function confirmPayment($params, $price){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pricePost = $this->loadPOST()['price'];
            if ($price !== $pricePost) {
                throw new PaymentException("Špatně zadaná částka, nutno zaplatit $price Kč!");
            }
            $query = 'UPDATE theatre.ticket SET is_paid = ? where id = ?';
            $this->db->run($query, ['Ano', $params[0]]);
            return true;
        }
    }

    public function checkURL($params){
        if(count($params) === 1){
            if(is_numeric($params[0])){
                return true;
            }
        }
        return false;
    }


	public function getTicket(): array
	{
		if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
			return $this->getAllTickets();
		}
		$data = $this->loadPOST();

		if (!isset($data['searchEmail'])) {
			return $this->getAllTickets();
		}
		if (empty($data['searchEmail'])) {
			return $this->getAllTickets();
		}

		$email = $data['searchEmail'];
		$query = 'select t.price, t.seat, t.discount, u.email, ce.date, ce.begin, cw.name from theatre.ticket as t 
				join theatre.user as u on t.id_user = u.id
				join theatre.culture_event as ce on t.id_culture_event = ce.id
				join theatre.culture_work as cw on ce.id_culture_work = cw.id
				where u.email = ?';

		return $this->db->run($query, $email)->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @return array
	 */
	private function getAllTickets(): array
	{
		$query = 'select t.price, t.seat, t.discount, u.email, ce.date, ce.begin, cw.name  from theatre.ticket as t 
				join theatre.user as u on t.id_user = u.id
				join theatre.culture_event as ce on t.id_culture_event = ce.id
				join theatre.culture_work as cw on ce.id_culture_work = cw.id';

		return $this->db->run($query)->fetchAll(PDO::FETCH_ASSOC);
	}


	public function processUpdate($data, &$updateOk)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dataToQuery = $this->loadPOST();
			$query = 'update theatre.ticket as t join theatre.user as u on t.id_user = u.id
					join theatre.culture_event as ce on t.id_culture_event = ce.id
					set t.price =?, t.seat = ?, t.discount = ?
					where u.email = ? and t.id_culture_event = ce.id';

			$res = $this->db->run($query, [$dataToQuery['price'], $dataToQuery['seat'], $dataToQuery['discount'], $dataToQuery['email']]);

			if ($res->errorCode() === '00000') {
				$updateOk = TRUE;
			}
		}
		$keys = ['price', 'seat', 'discount', 'email'];

		return array_combine($keys, $data);
	}

    public function stornoReservation($id)
    {
        $query = 'DELETE FROM theatre.ticket where ticket.id = ?';
        $this->db->run($query, $id);
    }
}