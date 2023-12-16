<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Logger.php
 * 17.12.2023 01:42
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Internal\Debug;

use Stringable;

/**
 * @class Logger
 * @package ANZ\Bitrix24\BasicPackage\Internal\Debug
 */
class Logger extends \Bitrix\Main\Diag\Logger
{
    const PATH_TO_LOG_FILE = '/local/logs/log.txt';

    /**
     * @param ...$vars
     */
    public static function print(...$vars): void
    {
        foreach ($vars as $var)
        {
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        }
    }

    /**
     * @param ...$vars
     * @return void
     */
    public static function printToFile(...$vars): void
    {
        $logger = new static();
        foreach ($vars as $var)
        {
            $logger->debug(print_r($var, true));
        }
    }

    /**
     * @param string $level
     * @param string $message
     * @return void
     */
    protected function logMessage(string $level, string $message): void
    {
        $log = date("d.m.Y H:i:s") . PHP_EOL . $message;
        file_put_contents(
            $_SERVER['DOCUMENT_ROOT'] . static::PATH_TO_LOG_FILE,
            $log . PHP_EOL,
            FILE_APPEND
        );
    }
}