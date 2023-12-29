<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - UserPermissions.php
 * 28.12.2023 18:18
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Service\Access;

use ANZ\Bitrix24\BasicPackage\Admin\Page\Option\MainSettingsPage;
use ANZ\Bitrix24\BasicPackage\Internal\Contract\Page\IPage;

/**
 * @class UserPermissions
 * @package ANZ\Bitrix24\BasicPackage\Service\Access
 */
class UserPermissions extends \Bitrix\Crm\Service\UserPermissions
{
    public function canViewPage(IPage $pageInstance): bool
    {
        if ($pageInstance->isAdminPage())
        {
            return $this->isAdmin();
        }

        $canView = true;

        switch (get_class($pageInstance))
        {
            case MainSettingsPage::class:
                $canView = false;
                break;
        }

        return $canView;
    }
}