<?php


namespace Authentication;


abstract class Validator
{

	protected function loadPOST(): array
	{
		$verifiedData = array_map([$this, 'parseInput'], $_POST);
		$this->languageMapper($verifiedData);

		return $verifiedData;
	}

	private function parseInput($data): string
	{
        return htmlspecialchars(stripslashes(trim($data)),ENT_QUOTES, 'UTF-8');
	}


	public function languageMapper(array &$data): void
	{

		$langDict = [
			'jmeno'          => 'firstName',
			'prijmeni'       => 'lastName',
			'heslo'          => 'password',
			'kontrolniHeslo' => 'controlPassword',
		];

		$roleDict = [
			'registrovaný uživatel' => 'registeredUser',
			'pokladní'              => 'cashier',
			'redaktor'              => 'editor',
		];

		foreach ($data as $key => $value) {
			if (array_key_exists($key, $langDict)) {
				$data[ $langDict[ $key ] ] = $value;
				unset($data[ $key ]);
			} elseif (array_key_exists($value, $roleDict)) {
				$data[ $key ] = $roleDict[ $value ];
			}
		}
	}

	protected function loadGET(): array
    {
        return (array_map([$this, 'parseInput'], $_GET));
    }
}