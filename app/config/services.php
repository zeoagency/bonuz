<?php

use Libs\Auth\Auth;
use Libs\Discord;
use Libs\SlackApi;
use Phalcon\Crypt;
use Phalcon\Flash\Session as Flash;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine; // auth library
use Phalcon\Mvc\View\Engine\Volt as VoltEngine; // slack api
use Phalcon\Session\Adapter\Files as SessionAdapter;

// DEBUG
$debug = new \Phalcon\Debug();
$debug->listen();

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    //$url->setBaseUri($config->application->baseUri);
    $url->setBaseUri(getenv('APP_URL') . "/");

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                "compiledExtension" => ".compiled",
                'compileAlways' => true,
            ]);

            $compiler = $volt->getCompiler();
            $compiler->addFilter("md5", "md5"); // md5 to use

            $compiler->addFunction(
                "str_replace",
                function ($resolvedArgs, $exprArgs) use ($compiler) {
                    // Resolve the first argument
                    $firstArgument = $compiler->expression($exprArgs[0]['expr']);
                    $secondArgument = $compiler->expression($exprArgs[1]['expr']);
                    $thirdArgument = $compiler->expression($exprArgs[2]['expr']);
                    return "str_replace(" . $firstArgument . ", " . $secondArgument . ", " . $thirdArgument . ")";
                }
            );

            $compiler->addFunction( // calculate total gained bonuz from bonuz and comments
                "getTotalPoints",
                function ($key) {
                    return "\\SqlHelper::getTotalPoints({$key})";
                }
            );

            $compiler->addFunction( // calculate total gained bonuz from bonuz and comments
                "getAccountBonuz",
                function ($key) {
                    return "\\SqlHelper::accountBonuz({$key})";
                }
            );

            $compiler->addFunction(
                "preg_replace",
                function ($resolvedArgs, $exprArgs) use ($compiler) {
                    // Resolve the first argument
                    $firstArgument = $compiler->expression($exprArgs[0]['expr']);
                    $secondArgument = $compiler->expression($exprArgs[1]['expr']);
                    $thirdArgument = $compiler->expression($exprArgs[2]['expr']);
                    return "preg_replace(" . $firstArgument . ", " . $secondArgument . ", " . $thirdArgument . ")";
                }
            );

            $compiler->addFunction( // convert time to readable string
                "bonuzDateBeautify",
                function ($key) {
                    return "\\Helpers\\Comment::timeString({$key})";
                }
            );

            $compiler->addFunction( // convert comment to ui pattern
                "bonuzCommentBeautify",
                function ($key) {
                    return "\\Helpers\\Comment::beautify({$key})";
                }
            );
            $compiler->addFunction( // convert comment to ui pattern
                "strtotime",
                function ($key) {
                    return "strtotime({$key})";
                }
            );
            $compiler->addFunction(
                "date",
                function ($resolvedArgs, $exprArgs) use ($compiler) {
                    // Resolve the first argument
                    $firstArgument = $compiler->expression($exprArgs[0]['expr']);
                    $secondArgument = $compiler->expression($exprArgs[1]['expr']);;
                    return "date(" . $firstArgument . ", " . $secondArgument . ")";
                }
            );

            return $volt;
        },
        '.phtml' => PhpEngine::class,

    ]);

    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => $config->database->charset,
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error' => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice' => 'alert alert-info',
        'warning' => 'alert alert-warning',
    ]);
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});
session_start();

$di->set('auth', function () {
    return new Auth();
});

$di->set('slack', function () {
    return new SlackApi();
});

$di->set('discord', function () {
    return new Discord();
});

$di->set('mail', function () {
    return new Mail();
});

$di->set( // cookie encryption
    'crypt',
    function () {
        $crypt = new Crypt();
        $crypt->setKey('2w\3svd<bQ}zkPC^X\am3kYex'); // kiiiy !
        return $crypt;
    }
);

/*
 * to call static methods of Bonuz model
 */
$di->set(
    'sql_helper',
    function () {
        return new SqlHelper();
    }
);
