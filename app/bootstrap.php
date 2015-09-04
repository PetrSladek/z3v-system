<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

//$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');



\Nette\Forms\Container::extensionMethod('addDatePicker', function(\Nette\Forms\Container $container, $name, $label = NULL) {
    return $container[$name] = new \Nextras\Forms\Controls\DatePicker($label);
});
\Nette\Forms\Container::extensionMethod('addDateTimePicker', function(\Nette\Forms\Container $container, $name, $label = NULL) {
    return $container[$name] = new \Nextras\Forms\Controls\DateTimePicker($label);
});
\Nette\Forms\Container::extensionMethod('addTypeahead', function(\Nette\Forms\Container $container, $name, $label = NULL, $callback = NULL) {
    $control = new \Nextras\Forms\Controls\Typeahead($label, $callback);
    return $container[$name] = $control;
});




$container = $configurator->createContainer();
return $container;
