<?php

/**
 * 
 */

namespace App;

use Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * 
 * 
 * 
 */
class Helpers
{

    /**
     * 
     * 
     */
    static public function dd($args)
    {
        self::d($args);
        die;
    }

    /**
     * 
     * 
     */
    static public function d($args)
    {
        $e = new Exception();
        echo '============================================================' . PHP_EOL;
        echo $e->getTraceAsString() . PHP_EOL;
        echo '============================================================' . PHP_EOL;
        var_dump($args);
    }

    /**
     * 
     * 
     */
    public function write($msg, $level)
    {
        self::log("[SLIM] [$level] $msg", 'info');
    }

    /**
     * 
     * 
     */
    static public function log($msg, $type)
    {
        $logger = self::getLogger();

        switch (strtoupper($type)) {
            case "WARNING":
                $func = 'warning';
                break;
            case "ERROR":
                $func = 'error';
                break;
            default:
                $func = 'info';
        }

        $logger->$func($msg);
    }

    /**
     * 
     * 
     */
    static public function getLogger($name = 'app')
    {
        static $logger;

        if (!$logger && Config::getValue("log")) {
            $logger = new Logger($name);
            $logger->pushHandler(new StreamHandler(LOG . '/' . $name . '-logs-' . date('Ymd') . '.log', Logger::DEBUG));
        }

        return $logger;
    }

    /**
     * 
     * 
     */
    static public function toOneDimension($array = array(), $nested_key = false, array $final = array())
    {

        foreach ($array as $key => $element) {

            $full_key = ($nested_key === false ? '' : $nested_key . '.') . $key;
            if (!is_array($array[$key])) $final[$full_key] = $element;
            else $final = array_merge($final, self::toOneDimension($array[$key], $full_key, $final));
        }

        return $final;
    }
}
