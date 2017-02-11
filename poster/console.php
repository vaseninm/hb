<?php

try {
    define('BASE_DIR', __DIR__);
    
    require 'vendor/autoload.php';

    $autoloader = new Aura\Autoload\Loader();
    $autoloader->addPrefix('poster', BASE_DIR . '/src');
    $autoloader->register();

    $env = array_key_exists('COMMON_ENV', $_SERVER) ? $_SERVER['COMMON_ENV'] : 'development';
    $config = \vaseninm\configure\Configure::me()
        ->addConfig('production', 'main.production')
        ->addConfig('development', 'main.development')
        ->selectConfig($env)
    ;

    $vk = new \Vk($config->get('vk'));
    echo $vk->get_code_token() . PHP_EOL;

} catch (Exception $e) {
    throw $e;
}

