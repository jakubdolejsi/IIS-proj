<?php


namespace Models;


class CashierModel extends BaseModel
{
    public function checkSearchParameters(){
        $role = $this->auth->role()->getRoleFromSession();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getPostDataAndValidate();

            if ($data['id'] !== '' && $data['email'] !== '') {
                return $role->getTicketByEmailAndIdPOST();
            } else if ($data['id'] !== '') {
                return $role->getTicketByIdPOST();
            } else if ($data['email'] !== '') {
                return $role->getFutureTicketByEmailPOST();
            }
        }
//        return $role->getTodayTickets();
    }

    public function confirmReservation($params)
    {
        $ticket = $this->getPostDataAndValidate();

    }
}