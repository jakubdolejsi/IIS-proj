<?php


namespace ViewRenderer;


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
	 * @param array $data
	 */
	public function loadData(array $data): void ;

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