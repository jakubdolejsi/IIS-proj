<?php /** @noinspection ALL */
spl_autoload_register(function ($cls)
{
//	var_dump($cls);
    $ds = DIRECTORY_SEPARATOR;
    $dir = __DIR__;
    $cls = str_replace('\\', $ds, $cls);
    $prefix = '/home/users/xd/xdolej09/WWW/IIS/'.$cls . '.php';
    //FIXME advanced debug
//	echo '<pre>', print_r('class in autoloader: '.$prefix, TRUE), '</pre>';
    $file = "{$dir}{$ds}{$cls}.php";
    if (is_readable($file)) {
        require_once ($file);
    }
});