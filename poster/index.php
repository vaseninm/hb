<?php

try {
    define('BASE_DIR', __DIR__);
    
    require 'vendor/autoload.php';

    $autoloader = new Aura\Autoload\Loader();
    $autoloader->addPrefix('poster', BASE_DIR . '/src');
    $autoloader->register();
    
    $config = \vaseninm\configure\Configure::me()
        ->addConfig('production', 'main.production')
        ->selectConfig('production')
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
    
    $loop->addPeriodicTimer(30, function () use ($registry) {
        $cron = \Cron\CronExpression::factory('*/5 * * * *');

        if ($cron->isDue() && ! $registry->isCurrent()) {
            $registry->updatePrev();
            echo date('Y-m-d H:i:s') . PHP_EOL;
        }

        $vacancy = \poster\models\Vacancy::find(
            ['status' => \poster\models\Vacancy::STATUS_ACCEPTED],
            ['category' => 1],
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

