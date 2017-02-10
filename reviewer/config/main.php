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
            'СРОК', 'ПИСАТЬ', 'УДАЛЕНКА', 'СОБЕСЕДОВАНИЕ', 'РЕЗЮМЕ', 'ШТАТ'
        ],
    ],
    'categoryWords' => [
        Vacancy::CATEGORY_DESIGNER => [
            'ДИЗАЙНЕР', 'ФОТО', 'PHOTOSHOP', 'ILLUSTRATOR', 'INDESIGN', 'МАКЕТ', 'ИЛЛЮСТРАТОР', 'ИНДИЗАЙН',
            'ТИПОГРАФИЯ', 'ПЕЧАТЬ', 'ВИЗУАЛИЗАЦИЯ', 'АНИМАЦИЯ', 'ОФОРМЛЕНИЕ', 'ПОРТФОЛИО', 'МОУШНДИЗАЙНЕР',
            'ВЕБДИЗАЙНЕР', 'КЛЮЧ', 'РЕКЛАМА', 'БАННЕР', 'ПОЛИГРАФИЯ', 'РИСОВАНИЕ', 'ВЕКТОР', 'ОТРИСОВАТЬ', 'ЛОГО',
            'СТИЛЬ', 'ЦВЕТ', 'ШАБЛОН', 'ЛОГО', 'НАРИСОВАТЬ'
        ],
        Vacancy::CATEGORY_FRONTEND => [
            'ВЕРСТАЛЬЩИК', 'HTML', 'CSS', 'JQUERY', 'КРОССБРАУЗЕРНОСТЬ', 'ВЕРСТКА', 'ШАБЛОН', 'НАТЯНУТЬ'
        ],
        Vacancy::CATEGORY_PROGRAMMER => [
            'ПРОГРАММИСТ', 'PHP', 'JAVASCRIPT', 'РАЗРАБОТКА', 'КОД', 'ООП', 'РАЗРАБОТЧИК'
        ],
        Vacancy::CATEGORY_WEBMASTER => [
            'АДМИН', 'САЙТ', 'ПЛАТФОРМА', 'НАСТРОИТЬ', 'ПРАВКА', 'СИСТЕМА', 'ШАБЛОН'
        ],
    ],
];