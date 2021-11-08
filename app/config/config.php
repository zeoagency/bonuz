<?php
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', constant('BASE_PATH') . '/app');
require_once constant('BASE_PATH') . '/vendor/autoload.php';
$dotenv = null;
try {

    $dotenv = Dotenv\Dotenv::createImmutable(constant('BASE_PATH'));
    $dotenv->load();
}
catch(\Exception $e) {
    
}

return new \Phalcon\Config([
    'database' => [
        'adapter' => $_ENV['DATABASE_ADAPTER'],
        'host' => $_ENV['DATABASE_HOST'],
        'username' => $_ENV['DATABASE_USERNAME'],
        'password' => $_ENV['DATABASE_PASSWORD'],
        'dbname' => $_ENV['DATABASE_DBNAME'],
        'charset' => 'utf8mb4',
    ],
    'application' => [
        'appDir' => constant('APP_PATH') . '/',
        'controllersDir' => constant('APP_PATH') . '/controllers/',
        'modelsDir' => constant('APP_PATH') . '/models/',
        'migrationsDir' => constant('APP_PATH') . '/migrations/',
        'viewsDir' => constant('APP_PATH') . '/views/',
        'pluginsDir' => constant('APP_PATH') . '/plugins/',
        'libraryDir' => constant('APP_PATH') . '/library/',
        'helperDir' => constant('APP_PATH') . '/helpers/',
        'servicesDir' => constant('APP_PATH') . '/services/',
        'cacheDir' => constant('BASE_PATH') . '/cache/',
        'vendorDir' => constant('BASE_PATH') . '/vendor/',
        'publicUrl' => $_ENV['APP_URL'],

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri' => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),

    ],

    'mail' => [
        'fromName' => $_ENV['MAIL_FROM_NAME'],
        'fromEmail' => $_ENV['MAIL_FROM_EMAIL'],
        'smtp' => [
            'server' => $_ENV['MAIL_SERVER'],
            'port' => $_ENV['MAIL_PORT'],
            'security' => $_ENV['MAIL_SECURITY'],
            'username' => $_ENV['MAIL_USERNAME'],
            'password' => $_ENV['MAIL_PASSWORD'],
        ],
    ],

]);
