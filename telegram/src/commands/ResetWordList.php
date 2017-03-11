<?php

namespace telegram\commands;

use telegram\models\CategoryWord;
use vaseninm\configure\Configure;

class ResetWordList extends AbstractCommand
{
    public function getDialogItems()
    {
        return [
            'Вы уверены, что хотите сбросить слова категорий к стандартным? Напишите "Да" для подтверждения',
            null,
        ];
    }

    public function afterFinish()
    {
        $answer = mb_strtolower($this->command->arguments[0]);

        if ($answer !== 'да')
            return $this->send("Сброс настроек отменен");

        $array = Configure::me()->get('categoryWords');

        CategoryWord::drop();
        
        foreach ($array as $category => $words) {

            foreach ($words as $key => $weight) {
                if (is_string($weight)) {
                    $key = $weight;
                    $weight = Configure::me()->get('categoryDefaultWeight');
                }

                $word = new CategoryWord();
                $word->category = $category;
                $word->word = $key;
                $word->weight = $weight;
                $word->save();
            }
        }

        $this->send("Сброс настроек успешно произведен");
    }
}