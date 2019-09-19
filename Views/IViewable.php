<?php


namespace Views;


/**
 * Interface IViewable
 * @package Views
 */
interface IViewable
{
	/**
	 * @return mixed
	 */
	public function render();

	/**
	 * @return mixed
	 */
	public function renderBase();

	/**
	 * @param $controller
	 * @return mixed
	 */
	public function loadController($controller);

	/**
	 * @param $baseView
	 * @return mixed
	 */
	public function loadBaseView($baseView);

	/**
	 * @param $controllerView
	 * @return mixed
	 */
	public function loadControllerView($controllerView);

}