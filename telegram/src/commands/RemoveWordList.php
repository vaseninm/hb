<?php

namespace telegram\commands;

use telegram\exceptions\WrongFormatException;
use telegram\models\CategoryWord;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use vaseninm\configure\Configure;

class RemoveWordList extends AbstractCommand
{
    public function getDialogItems()
    {
        return [
            $this->printList(),
            'Введите список слов вида "слово[новая строчка]слово"',
            null,
        ];
    }

    public function printList()
    {
        $list = array_keys(Configure::me()->get('categoryWords'));
        $text = 'Выберете категорию из которой необходимо удалить слова';

        $list = array_chunk($list, 3);
        $keyboard = new ReplyKeyboardMarkup($list, true);

        return [$text, $keyboard];
    }

    public function afterFinish()
    {
        $category = mb_strtolower($this->command->arguments[0]);
        $words = mb_strtolower($this->command->arguments[1]);

        if (! array_key_exists($category, Configure::me()->get('categoryWords')))
            return $this->send("Категория не найдена");

        $words = explode(PHP_EOL, $words);
        $words = array_map('trim', $words);

        try {
            $words = array_map(function ($word) {
                $word = mb_strtoupper(trim($word));

                if (empty($word)) return $this->send("Неверный формат");

                return $word;
            }, $words);
        } catch (WrongFormatException $e) {
            return $this->send($e->getMessage());
        }

        foreach ($words as $word) {
            $model = CategoryWord::find([
                'category' => $category,
                'word' => $word,
            ]);
            
            $model->delete();
        }

        $this->send("Слова из категории удалены. Проверьте список через /getWordList");
    }
}