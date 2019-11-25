<?php


namespace Controllers;


use Exceptions\AlreadyOccupiedSeatException;
use Exceptions\InvalidRequestException;
use Exceptions\ReservationSuccessException;
use Exceptions\SqlSomethingGoneWrongException;


class ReservationController extends BaseController
{
	public function process(array $params): void
	{
		$user = $this->getModelFactory()->createUserModel();
		if ($user->isLogged()) {
			$this->loadView('reservation');

			try {
				$user->createReservation($params);
			}
			catch (InvalidRequestException $e) {
				$this->alert($e->getMessage());
				$this->redirect('search');
			}
			catch (AlreadyOccupiedSeatException $e) {
				$this->alert($e->getMessage());
			}
			catch (SqlSomethingGoneWrongException $e) {
				$this->alert($e->getMessage());
			}
			catch (ReservationSuccessException $e) {
				$this->alert($e->getMessage());
				$this->redirect('ticket');
			}
		}else{
            $this->loadView('reservationUnregistered');

            $registeredOK = $user->oneTimeRegister();
            if ($registeredOK) {
                $user = $this->getModelFactory()->createUserModel();

                try {
                    $user->createReservation($params);
                }
                catch (InvalidRequestException $e) {
                    $this->alert($e->getMessage());
                    $this->redirect('search');
                }
                catch (AlreadyOccupiedSeatException $e) {
                    $this->alert($e->getMessage());
                }
                catch (SqlSomethingGoneWrongException $e) {
                    $this->alert($e->getMessage());
                }
                catch (ReservationSuccessException $e) {
                    $this->alert($e->getMessage());
                    $this->redirect('home');
                }
            }
        }


		//		$this->alert('Reservation was successfully created!');
		//		$this->redirect('tickets');
	}
}