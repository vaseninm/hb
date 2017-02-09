<?php

namespace grabber\models;

use Purekid\Mongodm\Model;

/**
 * 
 * @property string $title
 * @property string $namespace
 * @property \MongoTimestamp $lastImport
 * @property bool $enabled
 */
class Supplier extends \Purekid\Mongodm\Model
{

    static $collection = "suppliers";

    protected static $attrs = array(
        'namespace' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'title' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'lastImport' => [
            'type' => Model::DATA_TYPE_TIMESTAMP,
        ],
        'enabled' => [
            'type' => Model::DATA_TYPE_BOOLEAN,
        ],
    );
}