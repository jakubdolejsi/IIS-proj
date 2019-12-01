<?php


namespace Models;


use Exceptions\InvalidRequestException;


class CashierModel extends BaseModel
{
    public function checkSearchParameters(){
        $role = $this->auth->role()->getRoleFromSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	        $data = $this->loadPOST();

            if ($data['id'] !== '' && $data['email'] !== '') {
                $tickets = $role->getTicketByEmailAndIdPOST();
            } else if ($data['id'] !== '') {
                $tickets = $role->getTicketByIdPOST();
            } else if ($data['email'] !== '') {
                $tickets = $role->getFutureTicketByEmailPOST();
            }
        }
        if(isset($tickets)){
            if($tickets !== null){
                return $tickets;
            }
        }
        return [];
    }

    public function createReservationCashier($params){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        	$data = $this->loadPOST();
            $role = $this->auth->role()->getRoleFromSession();
            $role->newReservation($params, $data);
        }
    }

    public function checkURLParamsConfirm($params){
        $role = $this->auth->role()->getRoleFromSession();

        if(isset($params[0])){
            if($params[0] !== ''){
                if($params[0] === 'payment'){
                    $role->confirmPayment($params[1]);
                    return true;
                }else if($params[0] === 'storno'){
                    $role->stornoReservation($params[1]);
                    return true;
                }else{
                    throw new InvalidRequestException();
                }
            }
        }
        return false;
    }

    public function confirmReservation($params)
    {
	    $ticket = $this->loadPOST();

    }
}