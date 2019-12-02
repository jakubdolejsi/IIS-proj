<?php


namespace Controllers;


use Exceptions\UpdateException;
use Exceptions\UpdateSuccess;


class EditorController extends BaseController
{

	public function actionEvents($params): void
	{
		$this->hasPermission('admin', 'editor');

		$editor = $this->getModelFactory()->createEditorModel();


		$method = $this->getMethod($params, __FUNCTION__);
		try {
			[$data, $view] = method_exists($editor, $method) ? $editor->$method($params) : $this->redirect('error');
		}
		catch (UpdateException $exception) {
			$this->alert($exception->getMessage());
			$this->redirect('editor/events');
		}

		$this->loadView($view);
		$this->data['events'] = $data;
	}

	public function actionWorks($params): void
	{
		$this->hasPermission('admin', 'editor');

		$editor = $this->getModelFactory()->createEditorModel();
		$method = $this->getMethod($params, __FUNCTION__);
		try {
			[$data, $view] = method_exists($editor, $method) ? $editor->$method($params) : $this->redirect('error');
		}
		catch (UpdateException $exception) {
			$this->alert($exception->getMessage());
			$this->redirect('editor/works');
		}

		$this->loadView($view);
		$this->data['works'] = $data;
	}

	public function actionHalls($params): void
	{
		$this->hasPermission('admin', 'editor');

		$editor = $this->getModelFactory()->createEditorModel();

		$method = $this->getMethod($params, __FUNCTION__);
		try {
			[$data, $view] = method_exists($editor, $method) ? $editor->$method($params) : $this->redirect('error');
		}
		catch (UpdateException $exception) {
			$this->alert($exception->getMessage());
		}
		catch (UpdateSuccess $exception) {
			$this->alert($exception->getMessage());
		}

		$this->loadView($view);
		$this->data['hall'] = $data;
	}

	public function actionDefault(): void
	{
		$this->hasPermission('admin', 'editor');

		$this->loadView('editor');
	}

	private function getMethod(&$params, $func)
	{
		$method = $params[0] ?? 'default';
		unset($params[0]);
		$methodPostFix = substr(str_replace('action', '', $func), 0, -1);

		return $method . $methodPostFix;
	}
}