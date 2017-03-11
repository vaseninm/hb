<?php

use telegram\models\Vacancy;

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
        'users' => ['vaseninm', 'symbiosiz'],
    ],
    'categoryDefaultWeight' => 0.34,
    'categoryWords' => [
        Vacancy::CATEGORY_DESIGNER => [
            'ДИЗАЙНЕР' => 1, 'ФОТО', 'PHOTOSHOP', 'ILLUSTRATOR', 'INDESIGN', 'МАКЕТ', 'ИЛЛЮСТРАТОР' => 1, 'ИНДИЗАЙН',
            'ТИПОГРАФИЯ', 'ПЕЧАТЬ', 'ВИЗУАЛИЗАЦИЯ', 'АНИМАЦИЯ', 'ОФОРМЛЕНИЕ', 'ПОРТФОЛИО', 'МОУШНДИЗАЙНЕР',
            'ВЕБДИЗАЙНЕР', 'КЛЮЧ', 'РЕКЛАМА', 'БАННЕР', 'ПОЛИГРАФИЯ', 'РИСОВАНИЕ', 'ВЕКТОР', 'ОТРИСОВАТЬ', 'ЛОГО',
            'СТИЛЬ', 'ЦВЕТ', 'ШАБЛОН', 'ЛОГО', 'НАРИСОВАТЬ', 'ДИЗАЙН'
        ],
        Vacancy::CATEGORY_FRONTEND => [
            'ВЕРСТАЛЬЩИК' => 1, 'HTML', 'CSS', 'JQUERY', 'КРОССБРАУЗЕРНОСТЬ', 'ВЕРСТКА', 'ШАБЛОН', 'НАТЯНУТЬ'
        ],
        Vacancy::CATEGORY_PROGRAMMER => [
            'ПРОГРАММИСТ' => 1, 'PHP', 'JAVASCRIPT', 'JAVA', 'РАЗРАБОТКА', 'РАЗРАБАТЫВАТЬ', 'КОД', 'ООП', 'РАЗРАБОТЧИК' => 1,
            'ПРОГРАММИРОВАНИЕ', 'ПРОГРАММА',
        ],
//        Vacancy::CATEGORY_WEBMASTER => [
//            'АДМИН' => 1, 'САЙТ', 'ПЛАТФОРМА', 'НАСТРОИТЬ', 'ПРАВКА', 'СИСТЕМА', 'ШАБЛОН'
//        ],
    ],
];