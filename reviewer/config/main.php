<?php

use reviewer\models\Vacancy;

return [
    'mongo' => [
            'connection' => [
                'hostnames' => 'mongodb',
                'database'  => $_SERVER['COMMON_MONGO_DATABASE'],
                'username'  => $_SERVER['COMMON_MONGO_USERNAME'],
                'password'  => $_SERVER['COMMON_MONGO_PASSWORD'],
                'options' => [],
            ],
    ],
    'censor' => [
        'stopWords' => [
        ],
        'goodWords' => [
            'ОПЛАЧИВАТЬСЯ', 'РАБОТАТЬ', 'ОФИС', 'ТРЕБОВАНИЕ', 'РАБОТА', 'ГРАФИК', 'ОПЫТ', 'ИСКАТЬ', 'ТРЕБОВАТЬСЯ',
            'СРОК', 'ПИСАТЬ', 'УДАЛЕНКА', 'СОБЕСЕДОВАНИЕ', 'РЕЗЮМЕ', 'ШТАТ', 'УМЕТЬ', 'ХОТЕТЬ', 'ПРЕДЛОЖИТЬ', 'КОЛЛЕГА'
        ],
    ],
];