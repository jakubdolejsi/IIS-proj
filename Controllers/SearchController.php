<?php


namespace Controllers;


class SearchController extends BaseController
{


	public function actionEvent($params)
	{
		$events = $this->getModelFactory()->createCultureWork()->getCultureWorkByEvent($params[0]);
		$this->loadView('concreteEvent');
		$this->data['events'] = $events;
	}

	public function actionDefault(): void
	{
		$search = $this->getModelFactory()->createSearchModel();
		$events = $search->process();
		$this->loadView('search');
		$this->data['events'] = $events;
	}
}