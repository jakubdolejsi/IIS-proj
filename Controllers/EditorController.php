<?php


namespace Controllers;


use Exceptions\UpdateException;
use Exceptions\UpdateSuccess;


class EditorController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		try {
			$user = $this->getModelFactory()->createUserModel();
			$view = '';
			if (!isset($params[1])) {
				$this->loadView('editor');
			} elseif ($params[1] === 'events') {
				$this->loadView('editorEvents');
				$user->eventAction($params);
			} elseif ($params[1] === 'works') {
				$this->loadView('editorWorks');
			} elseif ($params[1] === 'halls') {
				$halls = $this->getModelFactory()->createHallModel();
				[$view, $data] = $user->hallAction($params, $halls);
				$this->loadView($view);
				$this->data['halls'] = $data;
			} else {
				$this->loadView('error404');
			}
		}
		catch (UpdateSuccess $exception) {
			$this->alert($exception->getMessage());
			$this->redirect('editor');
		}
		catch (UpdateException $exception) {
			$this->alert($exception->getMessage());
		}
	}
}