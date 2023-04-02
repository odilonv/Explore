<?php

namespace Explore\Lib\ObserverP;

abstract class Observable
{
    /**
     * @var Observer[] $observers
     */
    protected array $observers = [];
    public function addObserver(Observer $observer){
        if(!array_search($observer, $this->observers)) {
            $this->observers[] = $observer;
        }
    }
    public function removeObserver(Observer $observer){
        unset($this->observers[array_search($observer, $this->observers)]);
    }

    public abstract function notifyAll();
}