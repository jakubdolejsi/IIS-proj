<?php


namespace ViewRenderer;


/**
 * Interface IViewable
 * @package Views
 */
interface IViewable
{
	/**
	 * @param $baseView
	 * @return mixed
	 */
	public function loadBaseView(string $baseView): void;

	/**
	 * @param $controllerView
	 * @return mixed
	 */
	public function loadControllerView(string $controllerView): void;

	/**
	 * @param array $data
	 */
	public function loadData(array $data): void;

	/**
	 * @return mixed
	 */
	public function render(): void;

	/**
	 * @return mixed
	 */
	public function renderBase(): void;


}