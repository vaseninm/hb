<?php

namespace reviewer\services;

use reviewer\models\CategoryWord;
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


        $self->categoryWords = $self->getCategoryWords();

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

        $vacancyWords = $this->vacancy->getWords();

        $weightFromCategory = [];
        
        foreach ($this->categoryWords as $category => $words) {
            $weightFromCategory[$category] = 0;

            foreach ($vacancyWords as $word) {
                array_key_exists($word, $words) && $weightFromCategory[$category] += $words[$word];
            }
        }

        $maxValue = max($weightFromCategory);
        $maxCategory = array_search($maxValue, $weightFromCategory);
        
        if ($maxValue >= 1) {
            $this->vacancy->category = $maxCategory;
            $this->vacancy->save();

            $this->log("Vacancy [" . $this->vacancy->getId() . "] has new category: [" . $this->vacancy->category . "]");
        } else {
            $this->vacancy->status = Vacancy::STATUS_REJECTED;

            $this->log("Vacancy [" . $this->vacancy->getId() . "] has new status: [" . $this->vacancy->status . "]");
        }


        return $this;
    }

    public function getCategoryWords() {
        /**
         * @var CategoryWord[] $words
         */
        $words = CategoryWord::find();
        $array = [];

        foreach ($words as $word) {
            if (! array_key_exists($word->category, $array)) $array[$word->category] = [];

            $array[$word->category][$word->word] = $word->weight;
        }

        return $array;
    }

    public function log($message) {
        echo $message . PHP_EOL;
    }
}