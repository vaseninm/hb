<?php

return [
    'mongo' => [
            'connection' => [
                'hostnames' => 'mongodb',
                'database'  => 'grabber',
//                'username'  => '',
//                'password'  => '',
                'options' => [],
            ],
    ],
    'vk' => [
        'client_id' => $_SERVER['COMMON_VK_CLIENT_ID'], // (обязательно) номер приложения
        'secret_key' => $_SERVER['COMMON_VK_SECRET_KEY'], // (обязательно)
        'access_token' => $_SERVER['COMMON_VK_ACCESS_TOKEN'], // access_token
        'scope' => ['wall','groups'], // права доступа
    ],
    'period' => $_SERVER['GRABBER_PERIOD'],
];