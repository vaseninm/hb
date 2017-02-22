<?php

namespace telegram\commands;


use vaseninm\configure\Configure;

class GetVkAuthLink extends AbstractCommand
{
    public function getDialogItems()
    {
        return [
            null,
        ];
    }

    public function afterFinish()
    {
        $vk = new \Vk(Configure::me()->get('vk'));

        $this->send("Ссылка на авторизацию: {$vk->get_code_token()}");
    }
}