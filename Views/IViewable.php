<?php


namespace Views;


interface IViewable
{
	public function render();

	public function renderBase();

	public function loadController();

	public function loadBaseView();

	public function loadControllerView();

}