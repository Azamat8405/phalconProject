<?php

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Postgresql',
        'host'        => 'postgres_db',
        'username'    => 'db_user',
        'password'    => 'db_pass',
        'dbname'      => 'db_name'
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'models'         => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'migrationsTsBased' => false,
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'baseUri'        => '/',
    ],
    'swagger' => [
        'path' => APP_PATH,
        'host' => '/',
        'schemes' => 'http',
        'basePath' => '/',
        'version' => '2.0',
        'title' => 'Title',
        'description' => 'Description',
        'email' => 'azamat8405@yandex.ru',
        'jsonUri' => '/index/swaggerJson'
    ],
]);
