<?php

if (!isset($_SERVER['argv'][1])) {
	echo '
Generacate sample pairs

Usage: create-samplepairs.php <count_pairs>
';
	exit(1);
}

list(, $count) = $_SERVER['argv'];

$container = require __DIR__ . '/../app/bootstrap.php';

/** @var \Kdyby\Doctrine\EntityManager $em */
$em = $container->getByType(\Kdyby\Doctrine\EntityManager::class);
/** @var \App\Services\Users $users */
$users = $container->getByType('App\Services\Users');
/** @var \App\Services\Pairs $pairs */
$pairs = $container->getByType('App\Services\Pairs');
/** @var \App\Services\Races $races */
$races = $container->getByType('App\Services\Races');

$race = $races->findActualRace();


for ($i = 0; $i < $count; $i++)
{
	$user1 = $users->createUser("email$i-1@skautbk.cz", "pass$i-1");
	$user1->setAddress(new \App\Model\Address("Ulice $i-1", "666 99", "Mesto"));
	$user1->setFullName("Uživatel $i-1", "Příjmení $i-1", "Přezdívka $i-1");

	$user2 = $users->createUser("email$i-2@skautbk.cz", "pass$i-2");
	$user2->setAddress(new \App\Model\Address("Ulice $i-2", "666 99", "Mesto"));
	$user2->setFullName("Uživatel $i-2", "Příjmení $i-2", "Přezdívka $i-2");

	$em->flush();
	echo "Users to Pair $i added.\n";
	$pairs->createPair($race, $user1, $user2);
	echo "Pair $i. added.\n";
}



