<?php

if (!isset($_SERVER['argv'][2])) {
	echo '
Add new user to database.

Usage: create-user.php <name> <password>
';
	exit(1);
}

list(, $email, $password) = $_SERVER['argv'];

$container = require __DIR__ . '/../app/bootstrap.php';
/** @var \App\Services\Users $users */
$users = $container->getByType('App\Services\Users');

try {
	$users->createUser($email, $password);
	echo "User $email was added.\n";

} catch (\App\Model\DuplicateEmailException $e) {
	echo "Error: duplicate name.\n";
	exit(1);
}
