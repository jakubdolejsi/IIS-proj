<?php


namespace Controllers;


class SearchController extends BaseController
{


	public function actionEvent($params)
	{
		$event = $this->getModelFactory()->createSearchModel()->getCultureWorkByName($params);
		$this->loadView('concreteEvent');
		$this->data['event'] = $event;
	}

	public function actionDefault(): void
	{
		$search = $this->getModelFactory()->createSearchModel();
		$events = $search->process();
		$this->loadView('search');
		$this->loadData('events', $events);
        $this->data['currentTime'] = date('i-s');
        $this->data['todayDate'] = date('Y-m-s');
		$this->data['events'] = $events;
	}
}