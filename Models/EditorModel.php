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
			$data = $role->addHall($this->loadPOST());
		}

		return ['', 'EditorHallsAdd'];
	}



	public function defaultEvent()
	{
		$role = $this->auth->role()->getRoleFromSession();

		return [$role->getAllEvents(), 'EditorEvents'];
	}

	public function defaultHall()
	{
		$role = $this->auth->role()->getRoleFromSession();

		return [$role->getAllHalls(), 'EditorHalls'];
	}

	public function editEvent($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$data = $role->editEventByID($this->loadPOST(), $params);

			return [$data, 'EditorEventsEdit'];
		}
		$data = $role->getEventById($params);

		return [$data, 'EditorEventsEdit'];
	}

	public function editHall($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$data = $role->editHallbyId($this->loadPOST(), $params);

			return [$data, 'EditorHallsEdit'];
		}
		$data = $role->getHallById($params);

		return [$data, 'EditorHallsEdit'];
	}


	public function removeEvent($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		$role->removeEventByID($params);

		return ['', 'Editor'];
	}

	public function removeHall($params): array
	{
		$role = $this->auth->role()->getRoleFromSession();
		$role->removeHallbyId($params);

		return ['', 'Editor'];
	}

	public function defaultWork()
	{
		$role = $this->auth->role()->getRoleFromSession();

		return [$role->getAllWorks(), 'EditorWorks'];
	}

	public function addWork($params)
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$data = $this->loadPOST();
			$role = $this->auth->role()->getRoleFromSession();

			$role->addWork($data);
		}

		return ['', 'EditorWorksAdd'];
	}

	public function editWork($params)
	{
		$role = $this->auth->role()->getRoleFromSession();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {

			$data = $role->editWorkById($this->loadPOST(), $params);

			return [$data, 'EditorWorksEdit'];
		}
		$data = $role->getWorksById($params);

		return [$data, 'EditorWorksEdit'];
	}

	public function removeWork($params)
	{

	}


}