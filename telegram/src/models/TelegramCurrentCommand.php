<?php

namespace telegram\models;

use Purekid\Mongodm\Model;
use TelegramBot\Api\Types\Update;

/**
 * 
 * @property integer $user_id
 * @property string $command
 * @property array $arguments
 * @property integer $status
 */
class TelegramCurrentCommand extends \Purekid\Mongodm\Model
{
    
    static $collection = "telegram_current_command";

    const STATUS_PROCESSED = 1;
    const STATUS_FINISH = 2;

    protected static $attrs = array(
        'user_id' => [
            'type' => Model::DATA_TYPE_INTEGER,
        ],
        'command' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'arguments' => [
            'type' => Model::DATA_TYPE_ARRAY,
        ],
        'status' => [
            'type' => Model::DATA_TYPE_INTEGER,
        ],
    );
}