<?php


namespace Controllers;


class EditorController extends BaseController
{

	/**
	 * @param $params
	 * @return mixed
	 */
	public function process(array $params): void
	{
		$this->loadView('editor');
		//		$x = 'Response is : ' . $_POST['price'];
		//		var_dump($x);
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			var_dump('postttt');
		}
		if (isset($_POST['price'])) {
			$this->data['price'] = $_POST['price'];
		}
	}
}