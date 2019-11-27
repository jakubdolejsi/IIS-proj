<?php


namespace Models;


class EditorModel extends BaseModel
{

	public function addEvent($params)
	{

	}

	public function addHall($params)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleFromSession();
			$data = $role->addHall($this->getPostDataAndValidate());
		}

		return ['', 'editorHallsAdd'];
	}

	public function addWork($params)
	{

	}

	public function dafaultWork($params)
	{

	}

	public function defaultEvent()
	{
		$role = $this->auth->role()->getRoleFromSession();

		return [$role->getAllEvents(), 'editorEvents'];
	}

	public function defaultHall()
	{
		$role = $this->auth->role()->getRoleFromSession();

		return [$role->getAllHalls(), 'editorHalls'];
	}

	public function editEvent($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$data = $role->editEventByID($this->getPostDataAndValidate(), $params);

			return [$data, 'editorEventsEdit'];
		}
		$data = $role->getEventById($params);

		return [$data, 'editorEventsEdit'];
	}

	public function editHall($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$data = $role->editHallbyId($this->getPostDataAndValidate(), $params);

			return [$data, 'editorHallsEdit'];
		}
		$data = $role->getHallById($params);

		return [$data, 'editorHallsEdit'];
	}

	public function editWork($params)
	{

	}

	public function removeEvent($params)
	{

	}

	public function removeHall($params): array
	{
		$role = $this->auth->role()->getRoleFromSession();
		$role->removeHallbyId($params);

		return ['', 'editor'];
	}

	public function removeWork($params)
	{

	}
}