<?php


namespace Controllers;


class SearchController extends BaseController
{


	public function actionEvent($params)
	{
		$event = $this->getModelFactory()->createSearchModel()->getCultureWorkByName($params);
		$this->loadView('ConcreteEvent');
		$this->data['event'] = $event;
	}

	public function actionDefault(): void
	{
		$search = $this->getModelFactory()->createSearchModel();
		$events = $search->process();
		$this->loadView('Search');
		$this->loadData('events', $events);
		//		$this->data['events'] = $events;
	}
}