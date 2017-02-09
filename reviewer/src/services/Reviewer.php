<?php

namespace reviewer\services;

use reviewer\models\Vacancy;
use vaseninm\configure\Configure;

class Reviewer
{
    /**
     * @var Vacancy
     */
    protected $vacancy = null;
    protected $stopWords = [];
    protected $goodWords = [];
    protected $categoryWords = [];

    public static function create()
    {
        $self = new self();
        
        $self->stopWords = Configure::me()->get('censor')['stopWords'];
        $self->goodWords = Configure::me()->get('censor')['goodWords'];
        $self->categoryWords = Configure::me()->get('categoryWords');

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

        $this
            ->censor()
            ->sorter();

        return $this;
    }

    public function censor()
    {
        $words = $this->vacancy->getWords();

        $intersect = array_intersect($words, $this->goodWords);

        $this->vacancy->status = (count($intersect) > 1) ? Vacancy::STATUS_ACCEPTED : Vacancy::STATUS_REJECTED;
        $this->vacancy->save();

        $this->log("Vacancy [" . $this->vacancy->getId() . "] has new status: [" . $this->vacancy->status . "]");


        return $this;
    }

    public function sorter()
    {
        if ($this->vacancy->status !== Vacancy::STATUS_ACCEPTED) return $this;

        $words = $this->vacancy->getWords();

        $intersect = [];

        foreach ($this->categoryWords as $category => $word) {
            $intersect[$category] = count(array_intersect($words, $word));
        }

        $maxValue = max($intersect);
        $maxCategory = array_search($maxValue, $intersect);
        
        if ($maxValue >= 2) {
            $this->vacancy->category = $maxCategory;
            $this->vacancy->save();
        }

        $this->log("Vacancy [" . $this->vacancy->getId() . "] has new category: [" . $this->vacancy->category . "]");

        return $this;
    }

    public function log($message) {
        echo $message . PHP_EOL;
    }
}