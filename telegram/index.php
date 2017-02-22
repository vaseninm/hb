<?php

use \telegram\models\TelegramLastUpdate;
use \telegram\controllers\BotHandler;
use \TelegramBot\Api\Types\Update;

try {
    define('BASE_DIR', __DIR__);
    
    require 'vendor/autoload.php';

    $autoloader = new \Aura\Autoload\Loader();
    $autoloader->addPrefix('telegram', BASE_DIR . '/src');
    $autoloader->register();

    $env = array_key_exists('COMMON_ENV', $_SERVER) ? $_SERVER['COMMON_ENV'] : 'development';
    $config = \vaseninm\configure\Configure::me()
        ->addConfig('production', 'main.production')
        ->addConfig('development', 'main.development')
        ->selectConfig($env)
    ;

    $botApi = new \TelegramBot\Api\BotApi($config->get('telegram')['token']);
    $botClient = new \TelegramBot\Api\Client($config->get('telegram')['token']);
    \Purekid\Mongodm\MongoDB::setConfigBlock('default', $config->get('mongo'));
    $loop = \React\EventLoop\Factory::create();
    
    $botClient->on(function (Update $update) {
        BotHandler::handle($update->getMessage());
    }, function (Update $update) use ($config) {
        return in_array($update->getMessage()->getFrom()->getUsername(), $config->get('telegram')['users']);
    });

    $loop->addPeriodicTimer(3, function () use ($botApi, $botClient) {
        $updates = $botApi->getUpdates(TelegramLastUpdate::getNext());
        if (! empty($updates)) {
            TelegramLastUpdate::importLastIdFromUpdates($updates);
            $botClient->handle($updates);
        }
    });

//    $loop->addTimer(5, function () use ($botApi, $botClient) {
//        \telegram\controllers\Command::me()->sendCategoryRequest();
//    });

    $loop->addPeriodicTimer(1, function() {pcntl_signal_dispatch();});
    $signal = function() {die;};
    pcntl_signal(SIGTERM, $signal);
    pcntl_signal(SIGHUP, $signal);
    pcntl_signal(SIGINT, $signal);

    $loop->run();

} catch (Exception $e) {
    throw $e;
}

