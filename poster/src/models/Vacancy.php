<?php

namespace poster\models;

use cijic\phpMorphy\Morphy;
use Purekid\Mongodm\Model;

/**
 * @property integer $id
 * @property string $text
 * @property string $category
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

    const CATEGORY_DESIGNER = 'designer';
    const CATEGORY_PROGRAMMER = 'programmer';
    const CATEGORY_COPYWRITER = 'copywriter';
    const CATEGORY_ADMIN = 'admin';
    const CATEGORY_WEBMASTER = 'webmaster';
    const CATEGORY_FRONTEND = 'frontend';

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
            'model'=> '\poster\models\Supplier',
        ],
        'status' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'importedAt' => [
            'type' => Model::DATA_TYPE_TIMESTAMP,
        ],
        'category' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'photo' => [
            'type' => Model::DATA_TYPE_STRING,
        ],
        'ownerId' => [
            'type' => Model::DATA_TYPE_INTEGER,
        ],
    );

    public function getWords()
    {
        $text = preg_replace('/[[:punct:]]/', '', $this->text);
        $words = preg_split("/\s+/", $text);
        $words = array_filter($words, function($item) {
            return mb_strlen($item, mb_detect_encoding($item)) > 3;
        });

        $words = array_filter($words, function($item) {
            return mb_strlen($item, mb_detect_encoding($item)) > 3;
        });

        $morphy = new Morphy('ru');

        $words = array_map(function($item) use ($morphy) {
            $item = mb_strtoupper($item);
            $item = $morphy->lemmatize($item);

            return ! empty($item) ? $item[0] : null;
        }, $words);

        $words = array_filter($words, function($item) use ($morphy) {
            if ($item === null) return false;

            $pos = $morphy->getPartOfSpeech($item);
            
            return $pos !== false && (in_array('С', $pos) || in_array('Г', $pos) || in_array('ИНФИНИТИВ', $pos));
        });

        $words = array_values($words);

        return $words;
    }

}