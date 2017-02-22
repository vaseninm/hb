<?php

namespace telegram\commands;


use telegram\models\TelegramCurrentCommand;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use vaseninm\configure\Configure;

abstract class AbstractCommand
{
    /**
     * @var BotApi
     */
    public $botApi;
    /**
     * @var Message
     */
    public $message;
    /**
     * @var TelegramCurrentCommand
     */
    public $command;

    public function getDialogItems()
    {
        return [
        ];
    }

    public function getDialogCount()
    {
        $items = $this->getDialogItems();

        return count($items);
    }

    public function send(string $message, $keyboard = null) {
        return $this->botApi->sendMessage($this->message->getChat()->getId(), $message, null, null, null, $keyboard);
    }

    public function __construct(TelegramCurrentCommand $command, Message $message)
    {
        $this->botApi = new BotApi(Configure::me()->get('telegram')['token']);
        $this->message = $message;
        $this->command = $command;
    }

    public function go()
    {
        $currentStep = count($this->command->arguments);

        if ($currentStep < $this->getDialogCount() && $this->command->save()) {
            if ($currentStep === ($this->getDialogCount() - 1)) {
                $this->afterFinish();
            } else {
                $this->afterStep();
            }
        }
    }

    public function afterFinish()
    {
        $dialogItems = $this->getDialogItems();
        $currentStep = count($dialogItems) - 1;
        $answer = $dialogItems[$currentStep] ?? null;

        $keyboard = null;

        if (is_array($answer)) {
            $keyboard = $answer[1];
            $answer = $answer[0];
        }

        $this->send($answer, $keyboard);
    }
    
    public function afterStep()
    {
        $dialogItems = $this->getDialogItems();
        $currentStep = count($this->command->arguments);
        $answer = $dialogItems[$currentStep] ?? null;
        
        $keyboard = null;
        if (is_array($answer)) {
            $keyboard = $answer[1];
            $answer = $answer[0];
        }
        
        $this->send($answer, $keyboard);
    }
}