<?php

// Uncomment this line if you must temporarily take down your site for maintenance.
// require __DIR__ . '/.maintenance.php';

if (php_sapi_name() === 'cli-server')
{
	$_SERVER['SCRIPT_NAME'] = '/';
	if (is_file(__DIR__ . $_SERVER["REQUEST_URI"]))
	{
		return false;
	}
}


$container = require __DIR__ . '/../app/bootstrap.php';

$container->getByType('Nette\Application\Application')->run();
