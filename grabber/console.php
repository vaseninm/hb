<?php

try {
    define('BASE_DIR', __DIR__);
    
    require 'vendor/autoload.php';

    $autoloader = new Aura\Autoload\Loader();
    $autoloader->addPrefix('grabber', BASE_DIR . '/src');
    $autoloader->register();

    $env = array_key_exists('COMMON_ENV', $_SERVER) ? $_SERVER['COMMON_ENV'] : 'development';
    $config = \vaseninm\configure\Configure::me()
        ->addConfig('production', 'main.production')
        ->addConfig('development', 'main.development')
        ->selectConfig($env)
    ;

    \Purekid\Mongodm\MongoDB::setConfigBlock('default', $config->get('mongo'));
    \grabber\commands\Command::create()->run($argv);

} catch (Exception $e) {
    throw $e;
}

