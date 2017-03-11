<?php

namespace telegram\commands;

use telegram\exceptions\WrongFormatException;
use telegram\models\CategoryWord;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use vaseninm\configure\Configure;

class AddWordList extends AbstractCommand
{
    public function getDialogItems()
    {
        return [
            $this->printList(),
            'Введите список слов вида "слово:вес[новая строчка]слово:вес"',
            null,
        ];
    }

    public function printList()
    {
        $list = array_keys(Configure::me()->get('categoryWords'));
        $text = 'Выберете категорию в которую необходимо добавить слова';

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
            $words = array_map(function($word) {
                $word = explode(':', $word);
                $word = array_map('trim', $word);
                $word[0] = mb_strtoupper($word[0]);

                if (count($word) !== 2) throw new WrongFormatException('Неверный формат');
                if (empty($word[0]) || ! is_string($word[0])) throw new WrongFormatException('Неверный формат');
                if (empty($word[1]) || ! is_numeric($word[1])) throw new WrongFormatException('Неверный формат');

                return $word;
            }, $words);
        } catch (WrongFormatException $e) {
            return $this->send($e->getMessage());
        }

        foreach ($words as $word) {
            $model = CategoryWord::find([
                'category' => $category,
                'word' => $word[0],
            ])->first();
            
            if (! $model) {
                $model = new CategoryWord();
                $model->category = $category;
                $model->word = $word[0];
            }

            $model->weight = $word[1];
            $model->save();
        }

        $this->send("Слова сохранены в категории. Проверьте список через /getWordList");
    }
}