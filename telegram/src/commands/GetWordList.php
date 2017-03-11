<?php

namespace telegram\commands;

use telegram\models\CategoryWord;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use vaseninm\configure\Configure;

class GetWordList extends AbstractCommand
{
    public function getDialogItems()
    {
        return [
            $this->printList(),
            null,
        ];
    }

    public function printList()
    {
        $list = array_keys(Configure::me()->get('categoryWords'));
        $text = 'Выберете категорию в которой необходимо посмотреть слова';

        $list = array_chunk($list, 3);
        $keyboard = new ReplyKeyboardMarkup($list, true);

        return [$text, $keyboard];
    }

    public function afterFinish()
    {
        $category = mb_strtolower($this->command->arguments[0]);

        if (! array_key_exists($category, Configure::me()->get('categoryWords')))
            return $this->send("Категория не найдена");

        /**
         * @var CategoryWord[] $words
         */
        $words = CategoryWord::find([
            'category' => $category,
        ]);

        $text = 'Список слов в категории:' . PHP_EOL;

        foreach ($words as $word) {
            $text .= $word->word. ':' . $word->weight . PHP_EOL;
        }

        $this->send($text);
    }
}