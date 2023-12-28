<?php
use ANZ\Bitrix24\BasicPackage\Admin\Page\Option\MainSettingsPage;
use ANZ\Bitrix24\BasicPackage\Config\Options;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once ($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");

$pageInstance = new MainSettingsPage(new Options\Main());
$pageInstance->draw();

require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");