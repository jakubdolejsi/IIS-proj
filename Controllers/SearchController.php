<?php


namespace Controllers;


class SearchController extends BaseController
{


	public function actionDefault(): void
	{
		$search = $this->getModelFactory()->createSearchModel();
		$events = $search->process();
		$this->loadView('search');
		$this->loadData('events', $events);
		$this->data['currentTime'] = date('H-i-s');
		$this->data['todayDate'] = date('Y-m-d');
		$this->data['events'] = $events;
	}

	public function actionEvent($params)
	{
		$event = $this->getModelFactory()->createSearchModel()->getCultureWorkByName($params);
		$this->loadView('concreteEvent');
		$this->data['event'] = $event;
	}
}