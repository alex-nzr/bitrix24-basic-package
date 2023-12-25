<?php

use ANZ\Bitrix24\BasicPackage\Internal\Debug\Logger;
use ANZ\Bitrix24\BasicPackage\Internal\ServiceManager;

try
{
    if (is_file(__DIR__.'/../vendor/autoload.php'))
    {
        require_once __DIR__.'/../vendor/autoload.php';
        ServiceManager::getInstance()->start();
    }
}
catch (Throwable $e)
{
    Logger::printToFile($e->getMessage());
}