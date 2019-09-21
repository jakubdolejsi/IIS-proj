<?php


namespace Views\ViewRenderer;


class ViewFactory
{

	public function getViewRenderer(): ViewRenderer
	{
		return new ViewRenderer;
	}
}