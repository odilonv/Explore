<?php

namespace App\PlusCourtChemin\Lib;

class Utils
{
    public static bool $debug = true;
    private static float $startTime = 0;
    private static float $endTime = 0;
    private static bool $running = false;

    static function startTimer(){
        Utils::$running=true;
        Utils::$startTime = microtime(true);
    }

    static function getDuree(){
        if(Utils::$running) {
            return microtime(true) - Utils::$startTime;
        }
        else{
            return Utils::$endTime - Utils::$startTime;
        }
    }

    static function endTimer(){
        Utils::$running=false;
        Utils::$endTime = microtime(true);
    }
}