<?php

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
    'vk' => [
        'client_id' => $_SERVER['COMMON_VK_CLIENT_ID'], // (обязательно) номер приложения
        'secret_key' => $_SERVER['COMMON_VK_SECRET_KEY'], // (обязательно)
        'access_token' => $_SERVER['COMMON_VK_ACCESS_TOKEN'], // access_token
        'scope' => ['wall','groups', 'photos', 'offline'], // права доступа
    ],
    'telegram' => [
        'token' => $_SERVER['TELEGRAM_TOKEN'],
        'chat_id' => array_key_exists('TELEGRAM_CHAT_ID', $_SERVER) ? $_SERVER['TELEGRAM_CHAT_ID'] : null,
        'users' => ['vaseninm'],
    ],
];