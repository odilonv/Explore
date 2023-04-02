<?php
// a delete
namespace SAE\Lib\ObserverPattern;

abstract class ObserverManager{
    private array $observers = [];
    private array $obersvables = [];

    public function addObservable($observable){

    }
    public function addObserver($observable, $observer){
        $this->obersvables[$observable][] = $observer;
    }
}