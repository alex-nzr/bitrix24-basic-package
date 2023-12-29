<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - BaseAdminPage.php
 * 28.12.2023 18:39
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Admin\Page;

use ANZ\Bitrix24\BasicPackage\Internal\Contract\Page\IPage;
use CMain;

/**
 * @class BaseAdminPage
 * @package ANZ\Bitrix24\BasicPackage\Admin\Page
 */
abstract class BaseAdminPage implements IPage
{
    protected CMain $globalApp;

    public function __construct()
    {
        $this->globalApp = ($GLOBALS['APPLICATION'] instanceof CMain) ? $GLOBALS['APPLICATION'] : new CMain;
    }

    abstract public function checkAccess(): bool;
    abstract public function draw();

    /**
     * @return bool
     */
    public function isAdminPage(): bool
    {
        return true;
    }
}