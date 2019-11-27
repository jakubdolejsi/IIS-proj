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
		$this->data['events'] = $events;
	}
}