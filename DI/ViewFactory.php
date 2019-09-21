<?php


namespace DI;

use Views\ViewRenderer\ViewRenderer;


/**
 * Class ViewFactory
 * @package Views\ViewRenderer
 */
class ViewFactory
{
	/**
	 * @return ViewRenderer
	 */
	public function getViewRenderer(): ViewRenderer
	{
		return new ViewRenderer;
	}
}