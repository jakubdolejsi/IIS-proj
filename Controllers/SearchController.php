<?php


namespace Controllers;


class SearchController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		if (isset($params[1])) {
			$events = $this->getModelFactory()->createCultureWork()->getCultureWorkByEvent($params[1]);
			$this->loadView('concreteEvent');
		} else {
			$search = $this->getModelFactory()->createSearchModel();
			$events = $search->process();
			$this->loadView('search');
		}

		$this->data['events'] = $events;
	}
}