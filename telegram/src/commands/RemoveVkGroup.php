<?php

namespace telegram\commands;


use telegram\models\Supplier;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class RemoveVkGroup extends AbstractCommand
{
    public function getDialogItems()
    {
        return [
            $this->printList(),
            null
        ];
    }

    public function printList()
    {
        /**
         * @var Supplier[] $suppliers
         */
        $suppliers = Supplier::find();

        $list = [];
        $text = 'Выберете поставщика, которого необходимо удалить';

        foreach ($suppliers as $i => $supplier) {
            $list[] = $supplier->title;
        }

        $list = array_chunk($list, 3);
        $keyboard = new ReplyKeyboardMarkup($list, true);

        return [$text, $keyboard];
    }

    public function afterFinish()
    {
        $title = strtolower($this->command->arguments[0]);

        $model = Supplier::find(['title' => $title]);

        if (! $model)
            return $this->send("Группа [{$title}] уже существует");

        $model->delete();

        $this->send("Группа [{$title}] успешно удалена");
    }
}