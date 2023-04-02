<?php
// a delete
namespace Explore\Lib\ObserverP;

abstract class ObserverManager{
    private array $observers = [];
    private array $obersvables = [];

    public function addObservable($observable){

    }
    public function addObserver($observable, $observer){
        $this->obersvables[$observable][] = $observer;
    }
}