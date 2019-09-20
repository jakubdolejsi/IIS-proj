<?php


namespace Views\ViewRenderer;


use Controllers\aController;


/**
 * Interface IViewable
 * @package Views
 */
interface IViewable
{
	/**
	 * @return mixed
	 */
	public function render(): void ;

	/**
	 * @return mixed
	 */
	public function renderBase(): void ;

	/**
	 * @param $controller
	 * @return mixed
	 */
	public function loadController(aController $controller): void ;

	/**
	 * @param $baseView
	 * @return mixed
	 */
	public function loadBaseView(string $baseView): void ;

	/**
	 * @param $controllerView
	 * @return mixed
	 */
	public function loadControllerView(string $controllerView): void ;


}