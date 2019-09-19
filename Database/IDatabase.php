<?php


namespace Database;


interface IDatabase
{

	public function run($sql, $args = NULL);
}