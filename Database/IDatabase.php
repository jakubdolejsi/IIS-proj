<?php


namespace Database;


interface IDatabase
{

	public function run(string $sql, array $args = NULL);
}