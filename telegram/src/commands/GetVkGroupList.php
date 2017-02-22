<?php

namespace telegram\commands;


use telegram\models\Supplier;

class GetVkGroupList extends AbstractCommand
{
    public function getDialogItems()
    {
        return [
            $this->printResult(),
        ];
    }

    public function printResult()
    {
        /**
         * @var Supplier[] $suppliers
         */
        $suppliers = Supplier::find();

        $list = [];
        $list[] = 'Список поставщиков из вк:';

        foreach ($suppliers as $i => $supplier) {
            $list[] = "{$supplier->title} (" . ($supplier->enabled ? 'вкл' : 'выкл') . ")";
        }
        
        return implode(PHP_EOL, $list);
    }
}