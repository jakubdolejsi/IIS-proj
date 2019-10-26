<?php


namespace DI;


use ViewRenderer\ViewRenderer;


/**
 * Class ViewFactory
 * @package DI
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