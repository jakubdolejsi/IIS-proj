<?php


namespace Authentication\Roles;


class Editor extends RegisteredUser
{

	public function addAuditorium(){}

	public function removeAuditorium(){}

	// bude zde vice metod, jeste musim vymyslet jak budeme znazornovat
	// jednotlive udalosti/saly, predstaveni, obsah atd.

	public function editEvents(){}

	public function loadImg(){}

}