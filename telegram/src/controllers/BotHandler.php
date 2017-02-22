<?php
/**
 * Created by PhpStorm.
 * User: vaseninm
 * Date: 20.02.17
 * Time: 0:15
 */

namespace telegram\controllers;


use telegram\commands\AddVkGroup;
use telegram\commands\GetVkAuthLink;
use telegram\commands\GetVkGroupList;
use telegram\commands\Help;
use telegram\commands\RemoveVkGroup;
use telegram\models\TelegramCurrentCommand;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;

class BotHandler
{
    const COMMANDS = [
        'start' => Help::class,
        'help' => Help::class,
        'addVkGroup' => AddVkGroup::class,
        'getVkGroupList' => GetVkGroupList::class,
        'removeVkGroup' => RemoveVkGroup::class,
        'getVkAuthLink' => GetVkAuthLink::class,
    ];

    /**
     * @var Message
     */
    protected $message = null;
    
    public static function handle(Message $message)
    {
        $self = new self($message);

        return $self->init();
    }

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function init()
    {
        $command = $this->parseCommand();

        if ($command) {
            $model = $this->initModel($command);
        } else {
            $model = $this->getModel();
            $arguments = $model->arguments;
            $arguments[] = $this->message->getText();
            $model->arguments = $arguments;
        }

        $commandClass = array_key_exists($model->command, self::COMMANDS) ? self::COMMANDS[$model->command] : self::COMMANDS['help'];
        
        (new $commandClass($model, $this->message))->go();

        return $this;
    }

    public function getModel()
    {
        /**
         * @var TelegramCurrentCommand $model
         */
        $model = TelegramCurrentCommand::one([
            'user_id' => $this->message->getFrom()->getId(),
        ]);

        return $model;
    }

    public function initModel($command)
    {
        /**
         * @var TelegramCurrentCommand $model
         */
        $model = TelegramCurrentCommand::one([
            'user_id' => $this->message->getFrom()->getId(),
        ]);

        if (! $model) {
            $model = new TelegramCurrentCommand();
            $model->user_id = $this->message->getFrom()->getId();
            $model->status = TelegramCurrentCommand::STATUS_PROCESSED;
        }

        $model->command = $command;
        $model->arguments = [];

        $model->save();

        return $model;
    }

    public function parseCommand()
    {
        preg_match(Client::REGEXP, $this->message->getText(), $matches);

        if (!empty($matches)) {
            return $matches[1];
        }

        return null;
    }
}