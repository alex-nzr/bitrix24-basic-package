<?php
/**
 * @global \CMain $APPLICATION
 */

use ANZ\Bitrix24\BasicPackage\Config\Configuration;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$APPLICATION->SetTitle('Main settings');

require_once ($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");

try
{
    $module_id = Configuration::getInstance()->getBasicModuleId();

    if (!Loader::includeModule($module_id)){
        throw new Exception('MODULE_NOT_LOADED');
    }

    if ($APPLICATION->GetGroupRight($module_id) < 'W'){
        throw new Exception('ACCESS_DENIED');
    }

    $module_id = Configuration::getInstance()->getBasicModuleId();

    $optionManager = new \ANZ\Bitrix24\BasicPackage\Config\Options\Main($module_id);
    $optionManager->processRequest();
    $optionManager->startDrawHtml();

    $optionManager->tabControl->BeginNextTab();
    //require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");

    $optionManager->endDrawHtml();
}
catch (Exception $e){
    ShowError(Loc::getMessage($e->getMessage()));
}

require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");