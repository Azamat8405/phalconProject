<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
);

$loader->registerNamespaces([
    'App\Controllers'   => $config->application->controllersDir,
    'App\Models'        => $config->application->modelsDir,
    'App\Views'         => $config->application->viewsDir,
    'OpenApi'                       => '/var/www/html/vendor/zircote/swagger-php/src/',
    'Doctrine\Common\Annotations'   => '/var/www/html/vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/',
    'Doctrine\Common\Lexer'         => '/var/www/html/vendor/doctrine/lexer/lib/Doctrine/Common/Lexer/',
    'Symfony\Component\Finder'      => '/var/www/html/vendor/symfony/finder/',
    'Swagger' => '/var/www/html/vendor/zircote/swagger-php/src/'
])->register();
