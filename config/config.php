<?php

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../Desktop'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');


return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'postgresql',
        'host'        => 'postgres_db',
        'username'    => 'db_user',
        'password'    => 'db_pass',
        'dbname'      => 'db_name'
    ],
    'application' => [
        'appDir'         => '/var/www/html/application/app/',
        'controllersDir' => '/var/www/html/application/app/controllers/',
        'modelsDir'      => '/var/www/html/application/app/models/',
        'migrationsDir'  => '/var/www/html/application/app/migrations/',
        'migrationsTsBased' => false,
        'viewsDir'       => '/var/www/html/application/app/views/',
        'pluginsDir'     => '/var/www/html/application/app/plugins/',
        'libraryDir'     => '/var/www/html/application/app/library/',
        'cacheDir'       => '/var/www/html/application/cache/',
        'baseUri'        => '/',
    ]
]);
