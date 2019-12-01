<?php


namespace Authentication\Roles;

use PDO;


class Cashier extends RegisteredUser
{
    public function getHallByLabel($label)
    {
        $query = 'select h.id, h.label, h.seat_schema, h.capacity, h.column_count, h.row_count from xsvera04.hall as h where h.label = ?';

        return $this->db->run($query, $label)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @return mixed
     */
    public function getTicketByIdPOST()
    {
	    $data = $this->loadPOST();
        $query = 'select t.id, cw.name, ce.begin, ce.date, t.price, t.seat, h.label, t.payment_type,  t.is_paidfrom xsvera04.ticket as t 
				join xsvera04.user as u on t.id_user = u.id
				join xsvera04.culture_event as ce on t.id_culture_event = ce.id
				join xsvera04.culture_work as cw on ce.id_culture_work = cw.id
				join xsvera04.hall as h on ce.id_hall = h.id
				where t.id = ?';

        return $this->db->run($query, $data['id'])->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return mixed
     */
    public function getFutureTicketByEmailPOST()
    {

	    $data = $this->loadPOST();
        $query = 'select t.id, cw.name, ce.begin, ce.date, t.price, t.seat, h.label, t.payment_type, t.is_paid from xsvera04.ticket as t 
				join xsvera04.user as u on t.id_user = u.id
				join xsvera04.culture_event as ce on t.id_culture_event = ce.id
				join xsvera04.culture_work as cw on ce.id_culture_work = cw.id
				join xsvera04.hall as h on ce.id_hall = h.id
				where u.email = ? and ce.date >= ?';
        $date = date('Y-m-d');
        return $this->db->run($query, [$data['email'], $date])->fetchAll(PDO::FETCH_ASSOC);
    }




    /**
     * @return mixed
     */
    public function getTicketByEmailAndIdPOST()
    {
	    $data = $this->loadPOST();
        $query = 'select t.id, cw.name, ce.begin, ce.date, t.price, t.seat, h.label, t.payment_type, t.is_paid from xsvera04.ticket as t 
            join xsvera04.user as u on t.id_user = u.id
            join xsvera04.culture_event as ce on t.id_culture_event = ce.id
            join xsvera04.culture_work as cw on ce.id_culture_work = cw.id
            join xsvera04.hall as h on ce.id_hall = h.id
            where u.email = ? and t.id = ?';

        return $this->db->run($query, [$data['email'], $data['id']])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function confirmPayment($id)
    {
        $query = 'UPDATE xsvera04.ticket set ticket.is_paid=? where ticket.id = ?';
        $this->db->run($query, [1, $id]);
    }

    public function stornoReservation($id)
    {
        $query = 'DELETE FROM xsvera04.ticket where ticket.id = ?';
        $this->db->run($query, $id);
    }

    /**
     * @return mixed
     */
    public function getTodayTickets()
    {
        $date = date('Y-m-d');
        $query = 'select t.id, cw.name, ce.begin, ce.date, t.price, t.seat, h.label from xsvera04.ticket as t 
				join xsvera04.user as u on t.id_user = u.id
				join xsvera04.culture_event as ce on t.id_culture_event = ce.id
				join xsvera04.culture_work as cw on ce.id_culture_work = cw.id
				join xsvera04.hall as h on ce.id_hall = h.id
				where ce.date = ?';

        return $this->db->run($query, [$date])->fetchAll(PDO::FETCH_ASSOC);
    }

	public function newReservation($params, $data)
	{
		$user = 'defaultUser' . random_int(0,300);
		$userValues = [$user, $user, $user, $user, 'NotRegistered', 0,0];
		$createUserQuery = 'insert into xsvera04.user (firstName, lastName, email, password, role, hash, is_verified) 
						VALUES (?,?,?,?,?,?,?)';
		$x = $this->db->run($createUserQuery, $userValues);
		$id = $this->db->lastInsertId();
		$priceQuery = 'select price from xsvera04.culture_event where id = ?';
		$price = $this->db->run($priceQuery, $params[1])->fetch(PDO::FETCH_ASSOC);
		var_dump($price);
		var_dump($id);
		exit();
		$query = 'insert into xsvera04.ticket (id, id_user, id_culture_event, price, seat, discount, payment_type, is_paid) 
				VALUES (?,?,?,?,?,?,?,?)';
		$queryParams = [$id, $params[1], ];
	}

}