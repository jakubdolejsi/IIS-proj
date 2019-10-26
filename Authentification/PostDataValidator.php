<?php


namespace Authentification;


abstract class PostDataValidator
{
	protected function getPostDataAndValidate(): array
	{
		return (array_map([$this, 'parseInput'], $_POST));
	}

	private function parseInput($data): string
	{
		return (htmlspecialchars(stripslashes(trim($data))));
	}

}