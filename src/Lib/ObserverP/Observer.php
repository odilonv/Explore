<?php

namespace Explore\Lib\ObserverP;

interface Observer
{
    public function update(string $message);
}