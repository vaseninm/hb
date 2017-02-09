<?php

namespace grabber\importer;;

abstract class AbstractImporter implements ImporterInterface
{
    const NAMESPACE = '';
    protected $title = null;
    /**
     * @var \GearmanClient
     */
    protected $gearmanClient = null;

    /**
     * @return $this
     */
    public static function create() {
        $self = new static();
        
        return $self;
    }

    /**
     * @return $this
     */
    public function run() : ImporterInterface
    {
        $this
            ->log('Start import [' . static::NAMESPACE . ']')
            ->import()
            ->log('End import [' . static::NAMESPACE . ']')
        ;
        
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function log(string $message) : ImporterInterface
    {
        echo $message . PHP_EOL;
        
        return $this;
    }


    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title) : ImporterInterface
    {
        $this->title = $title;
        
        return $this;
    }

    /**
     * @param \GearmanClient $gearmanClient
     * @return $this
     */
    public function setGearmanClient(\GearmanClient $gearmanClient)
    {
        $this->gearmanClient = $gearmanClient;

        return $this;

    }

    /**
     * @return $this
     */
    public abstract function import() : ImporterInterface;
}