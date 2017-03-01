<?php

try {
    define('BASE_DIR', __DIR__);
    require 'vendor/autoload.php';

    $autoloader = new Aura\Autoload\Loader();
    $autoloader->addPrefix('reviewer', BASE_DIR . '/src');
    $autoloader->register();

    $env = array_key_exists('COMMON_ENV', $_SERVER) ? $_SERVER['COMMON_ENV'] : 'development';
    $config = \vaseninm\configure\Configure::me()
        ->addConfig('production', 'main.production')
        ->addConfig('development', 'main.development')
        ->selectConfig($env)
    ;

    \Purekid\Mongodm\MongoDB::setConfigBlock('default', $config->get('mongo'));


//    $item = \reviewer\models\Vacancy::find(['status' => \reviewer\models\Vacancy::STATUS_NEW])->first();
//    
//    \reviewer\services\Reviewer::create()
//        ->setVacancy($item)
//        ->run();
//
//    die;

    $worker = new GearmanWorker();
    $worker->addServer('gearman');

    $worker->addFunction('vacancy_create', function (GearmanJob $job) {
        $mongoId = $job->workload();
        
        $vacancy = \reviewer\models\Vacancy::id($mongoId);

        \reviewer\services\Reviewer::create()
            ->setVacancy($vacancy)
            ->run();
    });

    declare(ticks=1);
    $signal = function() {die;};
    pcntl_signal(SIGTERM, $signal);
    pcntl_signal(SIGHUP, $signal);
    pcntl_signal(SIGINT, $signal);

    $worker->setTimeout(10000);
    $worker->addOptions(GEARMAN_WORKER_NON_BLOCKING);

    while ($worker->work() || in_array($worker->returnCode(), [GEARMAN_IO_WAIT, GEARMAN_NO_JOBS, GEARMAN_TIMEOUT])) {
        if ($worker->returnCode() == GEARMAN_SUCCESS) {
            continue;
        }

        if (!$worker->wait())
        {
            if ($worker->returnCode() == GEARMAN_NO_ACTIVE_FDS)
            {
                sleep(5);
                continue;
            }
            elseif ($worker->returnCode() == GEARMAN_TIMEOUT)
            {
                continue;
            }
            
            break;
        }
    }

    echo "Worker Error: " . $worker->error() . "\n";

} catch (Exception $e) {
    throw $e;
}

