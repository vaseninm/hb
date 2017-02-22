<?php

namespace telegram\models;

use Purekid\Mongodm\Model;
use TelegramBot\Api\Types\Update;

/**
 * 
 * @property integer $id
 * @property integer $update_id
 */
class TelegramLastUpdate extends \Purekid\Mongodm\Model
{
    
    static $collection = "telegram_last_update";

    protected static $attrs = array(
        'id' => [
            'type' => Model::DATA_TYPE_INTEGER,
        ],
        'update_id' => [
            'type' => Model::DATA_TYPE_INTEGER,
        ],
    );

    /**
     * @return integer
     */
    public static function getNext()
    {
        /**
         * @var TelegramLastUpdate $item
         */
        $item = self::find(['id' => 1])->first();

        if (! $item) return 0;

        return $item->update_id + 1;
    }

    /**
     * @param Update[] $updates
     */
    public static function importLastIdFromUpdates($updates)
    {
        $item = self::find(['id' => 1]);
        
        if ($item->count() !== 1) {
            self::drop();
            $item = new self();
            $item->id = 1;
        } else {
            $item = $item->first();
        }

        $item->update_id = $updates[(count($updates) - 1)]->getUpdateId();
        $item->save();
    }
}