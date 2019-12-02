<?php


namespace Models;


use Exceptions\InvalidRequestException;


class CashierModel extends BaseModel
{
    public function checkSearchParameters(){
        $role = $this->auth->role()->getRoleFromSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	        $data = $this->loadPOST();

            if (!empty($data['id'])) {
                return $role->getTicketByIdPOST();
            } else if (!empty($data['email'])) {
                return $role->getFutureTicketByEmailPOST();
            }else if (!empty($data['hall'])){
                return $role->getTicketsByHallLabel();
            }else if(!empty($data['event'])){
                return $role->getTicketsByEventName();
            }else{
                return $role->getAllTickets();
            }
        }
        return $role->getAllTickets();
    }

    public function createReservationCashier($params){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        	$data = $this->loadPOST();
            $role = $this->auth->role()->getRoleFromSession();
            $role->newReservation($params, $data);
        }
    }

    public function checkURLParamsSearch($params){
        $role = $this->auth->role()->getRoleFromSession();

        if(isset($params[0])){
            if($params[0] !== ''){
                if($params[0] === 'confirm'){
                    $role->confirmPayment($params[1]);
                    return true;
                }else if($params[0] === 'remove'){
                    $role->removeReservation($params[1]);
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