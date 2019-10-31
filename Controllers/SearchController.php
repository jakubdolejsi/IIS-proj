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
		$search = $this->getModelFactory()->createSearchModel();
		$events = $search->process();

		$this->data['events'] = $events;
		$this->view = 'search';
		// TODO: Implement process() method.
	}
}