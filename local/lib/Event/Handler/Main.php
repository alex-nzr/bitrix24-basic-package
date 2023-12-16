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

use ANZ\Bitrix24\BasicPackage\Config\Configuration;
use ANZ\Bitrix24\BasicPackage\Config\Constants;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\UrlRewriter;
use Exception;

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
    public static function addCustomMenuGroup(&$globalAdminMenu, &$arModuleMenu): void
    {
        if (!defined('PROJECT_ADMIN_MENU_INCLUDED'))
        {
            define('PROJECT_ADMIN_MENU_INCLUDED', true);

            $moduleId = Configuration::getInstance()->getBasicModuleId();

            $settingsMenu = [
                'menu_id' => 'project_settings',
                'text' => 'Settings section',
                'title' => 'Settings section',
                'sort' => 1000,
                'items_id' => 'project_settings_items',
                'icon' => 'ui-icon ui-icon-service-site-b24 ui-icon-sm',
                'items' => [
                    [
                        'text' => 'Main settings',
                        'title' => 'Main settings',
                        'sort' => 60,
                        'url' => '/bitrix/admin/main-settings-page.php?lang=' . urlencode(LANGUAGE_ID),
                        'icon' => 'ui-icon ui-icon-service-wheel ui-icon-sm',
                        'page_icon' => 'main_settings_page_icon',
                    ]
                ],
            ];

            if(!is_array($globalAdminMenu))
            {
                $globalAdminMenu = [];
            }

            if (!isset($globalAdminMenu['global_menu_'.$moduleId]))
            {
                $globalAdminMenu['global_menu_'.$moduleId] = [
                    'menu_id' => 'global_menu_'.$moduleId,
                    'text' => 'Project',
                    'title' => 'Project',
                    'sort' => 1000,
                    'icon' => '',
                    'items_id' => 'global_menu_'.$moduleId.'_items',
                ];
            }

            $globalAdminMenu['global_menu_'.$moduleId]['items']['project_settings'] = $settingsMenu;
        }
    }
}