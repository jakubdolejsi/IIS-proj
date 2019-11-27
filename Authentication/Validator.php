<?php


namespace Authentication;


abstract class Validator
{

	protected function getPostDataAndValidate(): array
	{
		$verifiedData = array_map([$this, 'parseInput'], $_POST);
		$this->languageMapper($verifiedData);

		return $verifiedData;
	}

	private function parseInput($data): string
	{
		return htmlspecialchars(stripslashes(trim($data)));
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
}