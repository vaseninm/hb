<?php
namespace grabber\commands;


use grabber\importer\VkGroupImporter;
use grabber\models\Supplier;
use vaseninm\configure\Configure;

class Command
{
    public static function create()
    {
        return new self;
    }

    public function before()
    {
        return true;
    }

    public function after()
    {

    }

    public function getVkTokenAction()
    {
        $vk = new \Vk(Configure::me()->get('vk'));
        echo $vk->get_code_token() . PHP_EOL;
    }

    public function addNewVkSupplierAction($supplier)
    {
        if (! $supplier) throw new \Exception('Supplier argument is required');

        $suppliers = explode(',', $supplier);
        $suppliers = array_map('trim', $suppliers);

        foreach ($suppliers as $supplier) {
            $model = new Supplier();
            $model->title = $supplier;
            $model->namespace = VkGroupImporter::NAMESPACE;
            $model->enabled = true;
            if ($model->save()) echo "Supplier [{$supplier}] successfully added." . PHP_EOL;
        }
    }

    public function run($argv) {
        if (count($argv) < 2) throw new \Exception('Action is required');

        $action = $argv[1] . 'Action';
        $arguments = array_slice($argv, 2);

        if (! method_exists($this, $action)) throw new \Exception('Action not exist');

        if ($this->before()) {
            call_user_func_array([$this, $action], $arguments);
            $this->after();
        }
    }
}