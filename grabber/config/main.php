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
        'client_id' => $_SERVER['VK_CLIENT_ID'], // (обязательно) номер приложения
        'secret_key' => $_SERVER['VK_SECRET_KEY'], // (обязательно)
        'access_token' => $_SERVER['VK_ACCESS_TOKEN'], // access_token
        'scope' => ['wall','groups'], // права доступа
    ],
];