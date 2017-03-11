<?php

namespace telegram\commands;


class Help extends AbstractCommand
{
    public function getDialogItems()
    {
        return [
            $this->printResult(),
        ];
    }

    public function printResult()
    {
        $text = 'Hungry Brainz Api Bot' . PHP_EOL . PHP_EOL;
        $text .= "/start - Старт" . PHP_EOL;
        $text .= "/help - Помощь" . PHP_EOL;
        $text .= "/getvkgrouplist - Список поставщиков" . PHP_EOL;
        $text .= "/addvkgroup - Добавить поставщика" . PHP_EOL;
        $text .= "/removevkgroup - Удалить поставщика" . PHP_EOL;
        $text .= "/getvkauthlink - Получить ссылку для авторизации этого приложения" . PHP_EOL;
        $text .= "/resetwordlist - Сбросить список слов" . PHP_EOL;
        $text .= "/getwordlist - Получить список слов" . PHP_EOL;
        $text .= "/addwordlist - Добавить (или изменить) список слов с весами" . PHP_EOL;
        $text .= "/removewordlist - Удалить список слов" . PHP_EOL;

        return $text;
    }
}