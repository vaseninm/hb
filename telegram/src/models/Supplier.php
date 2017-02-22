<?php

namespace telegram\models;

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

    const TITLE_REGEXP = '/^[a-z0-9_]+$/i';

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