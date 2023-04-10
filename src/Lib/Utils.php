<?php

namespace Explore\Lib;

class Utils
{
    public static bool $debug = true;
    private static float $startTime = 0;
    private static float $endTime = 0;
    private static bool $running = false;

    private static array $logs = [];

    static function startTimer()
    {
        Utils::$running = true;
        Utils::$startTime = microtime(true);
    }

    static function getDuree()
    {
        if (Utils::$running) {
            return microtime(true) - Utils::$startTime;
        } else {
            return Utils::$endTime - Utils::$startTime;
        }
    }

    static function endTimer()
    {
        Utils::$running = false;
        Utils::$endTime = microtime(true);
    }

    static function log(string $message)
    {
        Utils::$logs[] = $message;
    }

    static function getLogs()
    {
        return Utils::$logs;
    }
}
