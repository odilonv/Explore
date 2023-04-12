<?php

namespace Explore\Configuration;

class ConfigurationBDDTestUnitaire implements ConfigurationBDDInterface
{
    public function getLogin(): string
    {
        return "";
    }

    public function getMotDePasse(): string
    {
        return "";
    }

    public function getDSN(): string
    {
        return "sqlite:".__DIR__."/database_test";
    }

    public function getOptions(): array
    {
        return array();
    }
}