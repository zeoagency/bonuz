<?php
use Phalcon\Di\FactoryDefault;

require_once './../vendor/autoload.php';

error_reporting($_ENV['DEBUG'] == 1 ? E_ALL ^ E_WARNING) :0;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', constant('BASE_PATH') . '/app');
try {

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Inject dotenv
     */





    /**
     * Handle routes
     */
    include APP_PATH . '/config/router.php';

    /**
     * Read services
     */
    include APP_PATH . '/config/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo str_replace(["\n", "\r", "\t"], '', $application->handle()->getContent());
} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}
