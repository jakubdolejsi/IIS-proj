<?php /** @noinspection ALL */

spl_autoload_register(function ($cls)
{
	$ds = DIRECTORY_SEPARATOR;
	$dir = __DIR__;
	$cls = str_replace('\\', $ds, $cls);
	$file = "{$dir}{$ds}{$cls}.php";
	if (is_readable($file)) {
		require_once ($file);
	}
});
