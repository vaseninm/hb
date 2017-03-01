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

    \Purekid\Mongodm\MongoDB::setConfigBlock('default', $config->get('mongo'));
    
    $loop = \React\EventLoop\Factory::create();

    $registry = new class {
        private $prev = null;

        public function updatePrev()
        {
            $this->prev = date('Y-m-d H:i');
        }

        public function isCurrent()
        {
            return $this->prev === date('Y-m-d H:i');
        }
    };
    
    $loop->addPeriodicTimer(30, function () use ($registry, $config) {
        $cron = \Cron\CronExpression::factory($config->get('community')['cron']);

        if (! ($cron->isDue() && ! $registry->isCurrent()) ) {
            return $registry->updatePrev();
        }

        $registry->updatePrev();

        $vacancy = \poster\models\Vacancy::find(
            [
                'status' => \poster\models\Vacancy::STATUS_ACCEPTED,
                'category' => ['$exists' => true]
            ],
            ['importedAt' => 1],
            [],
            1
        )->get();
        
        if (! $vacancy) {
            echo "No vacancy for posting. Skipped." . PHP_EOL;
            return false;
        }

        \poster\services\Poster::create()
            ->setVacancy($vacancy)
            ->run();

        return true;
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

