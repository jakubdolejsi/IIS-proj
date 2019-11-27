<?php


namespace Controllers;


class EditorController extends BaseController
{

	public function actionEvents($params)
	{
		$user = $this->getModelFactory()->createUserModel();
		$this->loadView('editorEvents');
		$user->eventAction($params);
	}

	public function actionWorks($params)
	{
		$user = $this->getModelFactory()->createUserModel();
		$this->loadView('editorWorks');
	}

	public function actionHalls($params)
	{
		$user = $this->getModelFactory()->createUserModel();
		$halls = $this->getModelFactory()->createHallModel();
		[$view, $data] = $user->hallAction($params, $halls);
		$this->loadView($view);
		$this->data['halls'] = $data;
	}

	public function actionDefault(): void
	{
		$this->loadView('editor');
	}
}