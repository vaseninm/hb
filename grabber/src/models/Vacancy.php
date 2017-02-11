<?php

namespace grabber\models;

use Purekid\Mongodm\Model;

/**
 * @property integer $id
 * @property string $text
 * @property Supplier $supplier
 * @property string $status
 * @property \MongoTimestamp $importedAt
 * @property string $photo
 * @property integer $ownerId
 */
class Vacancy extends \Purekid\Mongodm\Model
{

    const STATUS_NEW = 'new';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_POSTED = 'posted';

    static $collection = "vacancies";
    
    protected static $attrs = array(
        'id' => [
            'type' => Model::DATA_TYPE_INTEGER,
        ],
        'text' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'supplier' => [
            'type' => Model::DATA_TYPE_REFERENCE,
            'model'=> '\grabber\models\Supplier',
        ],
        'status' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'importedAt' => [
            'type' => Model::DATA_TYPE_TIMESTAMP,
        ],
        'photo' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'ownerId' => [
            'type' => Model::DATA_TYPE_INTEGER,
        ],
    );

}