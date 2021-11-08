<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->libraryDir,
        $config->application->helperDir,
        $config->application->servicesDir,
    ]
);

$loader->registerNamespaces([
    'Libs' => $config->application->libraryDir,
    'Helpers' => $config->application->helperDir,
    'Services' => $config->application->servicesDir,
]);

$loader->register();
