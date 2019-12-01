<?php


namespace Models;


class EditorModel extends BaseModel
{

	public function addEvent($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$data = $role->addEvent($this->loadPOST());
		}
		return [$role->getHallAndCultureWorkIds(), 'editorEventsAdd'];
	}

	public function addHall($params)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$role = $this->auth->role()->getRoleFromSession();
			$data = $role->addHall($this->loadPOST());
		}

		return ['', 'editorHallsAdd'];
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

			$data = $role->editEventByID($this->loadPOST(), $params);

			return [$data, 'editorEventsEdit'];
		}
		$data = $role->getEventById($params);

		return [$data, 'editorEventsEdit'];
	}

	public function editHall($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$data = $role->editHallbyId($this->loadPOST(), $params);

			return [$data, 'editorHallsEdit'];
		}
		$data = $role->getHallById($params);

		return [$data, 'editorHallsEdit'];
	}


	public function removeEvent($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		$role->removeEventByID($params);

		return ['', 'editor'];
	}

	public function removeHall($params): array
	{
		$role = $this->auth->role()->getRoleFromSession();
		$role->removeHallbyId($params);

		return ['', 'editor'];
	}

	public function defaultWork()
	{
		$role = $this->auth->role()->getRoleFromSession();

		return [$role->getAllWorks(), 'editorWorks'];
	}

	public function addWork($params)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = $this->loadPOST();
			$role = $this->auth->role()->getRoleFromSession();

			$role->addWork($data);
		}

		return ['', 'editorWorksAdd'];
	}

	public function editWork($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$data = $role->editWorkById($this->loadPOST(), $params);

			return [$data, 'editorWorksEdit'];
		}
		$data = $role->getWorksById($params);

		return [$data, 'editorWorksEdit'];
	}

	public function removeWork($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		$role->removeWorksByID($params);
		return [$role->getAllWorks(), 'editorWorks'];
	}


}