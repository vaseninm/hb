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
        $text = 'Hungry Brainz Api Bot' . PHP_EOL;
        $text .= "\t/start - Старт" . PHP_EOL;
        $text .= "\t/help - Помощь" . PHP_EOL;
        $text .= "\t/getVkGroupList - Список поставщиков" . PHP_EOL;
        $text .= "\t/addVkGroup - Добавить поставщика" . PHP_EOL;
        $text .= "\t/removeVkGroup - Удалить поставщика" . PHP_EOL;
        $text .= "\t/getVkAuthLink - Получить ссылку для авторизации этого приложения" . PHP_EOL;

        return $text;
    }
}