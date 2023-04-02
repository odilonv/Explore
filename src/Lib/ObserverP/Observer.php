<?php

namespace SAE\Lib\ObserverPattern;

interface Observer
{
    public function update(string $message);
}