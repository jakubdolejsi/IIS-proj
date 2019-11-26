<?php


namespace Controllers;


use Exceptions\CompleteRegistrationException;
use Exceptions\DuplicateUser;
use Exceptions\PasswordsAreNotSameException;
use Exceptions\UpdateException;
use Exceptions\UpdateSuccess;
use PDO;

class AdminController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
        try {
            if (!isset($params[1])) {
                $admin = $this->getModelFactory()->createAdminModel();
                $users = $admin->process();
                $this->loadView('admin');
                $this->data['users'] = $users;
            } elseif ($params[1] === 'add') {
                $user = $this->getModelFactory()->createUserModel();
                try{
                    $user->register();
                }
                catch (PasswordsAreNotSameException $e){
                    $this->alert($e->getMessage());
                }
                catch (DuplicateUser $e){
                    $this->alert($e->getMessage());
                }
                catch (CompleteRegistrationException $e){
                    $this->alert("");
                }
                $this->loadView('adminAddUser');
            } elseif ($params[1] === 'edit') {
                $this->loadView('adminEditUser');
            } else {
                $this->loadView('error404');
            }
        }
        catch (UpdateSuccess $exception) {
            $this->alert($exception->getMessage());
            $this->redirect('admin');
        }
        catch (UpdateException $exception) {
            $this->alert($exception->getMessage());
        }

	}
}