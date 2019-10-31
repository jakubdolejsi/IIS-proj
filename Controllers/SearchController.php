<?php


namespace Controllers;


class SearchController extends baseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{


		if (isset($params[1])) {
			$events = $this->getModelFactory()->createCultureWork()->getCultureWorkByEvent($params[1]);
			$this->view = 'concreteEvent';
		} else {
			$search = $this->getModelFactory()->createSearchModel();
			$events = $search->process();
			$this->view = 'search';
		}

		$this->data['events'] = $events;
	}
}