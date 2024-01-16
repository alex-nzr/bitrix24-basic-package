<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Main.php
 * 15.12.2023 22:21
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Event\Handler;

use ANZ\Bitrix24\BasicPackage\Admin;
use ANZ\Bitrix24\BasicPackage\Config\Configuration;

/**
 * @class Main
 * @package ANZ\Bitrix24\BasicPackage\Event\Handler
 */
class Main
{
    /**
     * @param $globalAdminMenu
     * @param $arModuleMenu
     * @return void
     */
    public static function onBuildGlobalMenu(&$globalAdminMenu, &$arModuleMenu): void
    {
        if (!is_array($globalAdminMenu))
        {
            $globalAdminMenu = [];
        }

        if (!is_array($arModuleMenu))
        {
            $arModuleMenu = [];
        }

        (new Admin\Menu\Manager)->processGlobalMenu($globalAdminMenu, $arModuleMenu);
    }
}