<?php

namespace telegram\commands;


use telegram\models\Supplier;

class AddVkGroup extends AbstractCommand
{
    public function getDialogItems()
    {
        return [
            'Напишите имя группы',
            'Группа успешно добавлена',
        ];
    }

    public function afterFinish()
    {
        $title = mb_strtolower($this->command->arguments[0]);

        if (Supplier::has(['title' => $title]))
            return $this->send("Группа [{$title}] уже существует");

        if (! preg_match(Supplier::TITLE_REGEXP, $title))
            return $this->send("[{$title}] может содержать только английские буквы, цифры и _");

        $model = new Supplier();
        $model->title = $title;
        $model->namespace = 'vk';
        $model->enabled = true;
        $model->save();

        $this->send("Группа [{$title}] успешно добавлена");
    }
}