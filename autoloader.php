<?php /** @noinspection ALL */


spl_autoload_register(function ($cls)
{
	$ds = DIRECTORY_SEPARATOR;
	$dir = __DIR__;
	$cls = str_replace('\\', $ds, $cls);
	$file = "{$dir}{$ds}{$cls}.php";
//    var_dump($file);

	$file = substr($file, 0, 15);
	$prefix = '/homes/eva/xs/xsvera04/WWW/IIS/'.$cls . '.php';
//	var_dump($file);
	echo '<br>' . $prefix;
	if (is_readable($prefix)) {
		require_once ($prefix);
	}
});
