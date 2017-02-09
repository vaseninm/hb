<?php

namespace poster\services;

use poster\models\Vacancy;
use vaseninm\configure\Configure;

class Poster
{
    const IMG_PATH = 'images/categories';
    const UNKNOWN = 'unknown';
    /**
     * @var Vacancy
     */
    protected $vacancy = null;

    private $vk = null;

    public static function create()
    {
        $self = new self();

        return $self;
    }

    /**
     * @param Vacancy $vacancy
     * @return $this
     */
    public function setVacancy(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;

        return $this;
    }

    public function run()
    {
        if ($this->vacancy === null) throw new \Exception('Vacancy not set');

        $img = BASE_DIR . '/' . self::IMG_PATH . '/';

        if ($this->vacancy->category && file_exists($img . $this->vacancy->category . '.png')) {
            $img .= $this->vacancy->category . '.png';
        } else {
            $img .= self::UNKNOWN . '.png';
        }
        
        $attachments = $this->getVk()->upload_photo($this->getCommunityId(), [$img]);
        $this->getVk()->api('wall.post', [
            'owner_id' => '-' . $this->getCommunityId(),
            'from_group' => 1,
            'signed' => 0,
            'message' => $this->getMessage(),
            'attachments' => implode(',', $attachments),
        ]);

        $this->vacancy->status = Vacancy::STATUS_POSTED;
        $this->vacancy->save();

        $this->log('Vacancy [' . $this->vacancy->getId() . '] is posted');

        return $this;
    }

    /**
     * @return \Vk
     */
    protected function getVk() : \Vk
    {
        if ($this->vk === null) {
            $this->vk = new \Vk(Configure::me()->get('vk'));
        }

        return $this->vk;
    }

    protected function getCommunityId() {
        return Configure::me()->get('community')['id'];
    }

    protected function getMessage() {
        $message = '';
        $message .= $this->vacancy->text;

        if ($this->vacancy->ownerId) {
            $message .= PHP_EOL . '@id2004952 (Автор сообщения 1), @id3398555 (Автор сообщения 2), ';
        }

        return $message;
    }

    protected function log($message) {
        echo $message . PHP_EOL;
    }
}