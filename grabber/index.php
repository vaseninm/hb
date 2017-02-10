<?php

try {
    define('BASE_DIR', __DIR__);
    
    require 'vendor/autoload.php';

    $autoloader = new \Aura\Autoload\Loader();
    $autoloader->addPrefix('grabber', BASE_DIR . '/src');
    $autoloader->register();

    $env = array_key_exists('COMMON_ENV', $_SERVER) ? $_SERVER['COMMON_ENV'] : 'development';
    $config = \vaseninm\configure\Configure::me()
        ->addConfig('production', 'main.production')
        ->addConfig('development', 'main.development')
        ->selectConfig($env)
    ;

    $gearmanClient = new GearmanClient();
    $gearmanClient->addServer('gearman', '4730');

    $loop = \React\EventLoop\Factory::create();

    \Purekid\Mongodm\MongoDB::setConfigBlock('default', $config->get('mongo'));
    
    $loop->addPeriodicTimer($config->get('period'), function() use ($gearmanClient) {
        \grabber\importer\VkGroupImporter::create()
            ->setGearmanClient($gearmanClient)
            ->run();
    });

    $loop->addPeriodicTimer(1, function() {pcntl_signal_dispatch();});
    $signal = function() {die;};
    pcntl_signal(SIGTERM, $signal);
    pcntl_signal(SIGHUP, $signal);
    pcntl_signal(SIGINT, $signal);

    $loop->run();
} catch (Exception $e) {
    throw $e;
}

