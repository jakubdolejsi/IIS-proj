<?php


namespace Views;



use Controllers\aView;


class View extends aView
{


	public function render()
	{
		if ($this->view) {
			//			extract($this->data);
			$this->includeView("Views/", $this->view);

		}
	}

	private function includeView($folder, $view){
		$path = $folder.$view.'.phtml';
		if (is_file($path)){
			require ($path);
		} else{
			require ($folder . 'Booking/' . $view . '.phtml');
		}
	}
}