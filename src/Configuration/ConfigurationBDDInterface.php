<?php

namespace Explore\Configuration;

interface ConfigurationBDDInterface
{

    public function getLogin() : string;
    public function getMotDePasse() : string;
    public function getDSN() : string;
    public function getOptions() : array;

}
