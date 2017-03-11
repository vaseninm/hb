<?php

namespace telegram\models;

use Purekid\Mongodm\Model;

/**
 * @property string $category
 * @property string $word
 * @property float $weight
 */
class CategoryWord extends \Purekid\Mongodm\Model
{
    static $collection = "category_words";
    
    protected static $attrs = array(
        'category' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'word' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'weight' => [
            'type' => Model::DATA_TYPE_FLOAT,
        ],
    );
}