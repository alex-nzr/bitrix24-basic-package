<?php
use ANZ\Bitrix24\BasicPackage\Internal\ServiceManager;

if (is_file(__DIR__.'/../vendor/autoload.php'))
{
    require_once __DIR__.'/../vendor/autoload.php';
    ServiceManager::getInstance()->start();
}