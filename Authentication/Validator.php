<?php


namespace Authentication;


abstract class Validator
{

	protected function getPostDataAndValidate(): array
	{
		return (array_map([$this, 'parseInput'], $_POST));
	}

	private function parseInput($data): string
	{
		return (htmlspecialchars(stripslashes(trim($data))));
	}

    protected function getGetDataAndValidate(): array
    {
        return (array_map([$this, 'parseInput'], $_GET));
    }
}