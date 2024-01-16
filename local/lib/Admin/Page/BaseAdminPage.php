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

use ANZ\Bitrix24\BasicPackage\Config\Configuration;
use ANZ\Bitrix24\BasicPackage\Internal\Contract\Page\IPage;
use ANZ\Bitrix24\BasicPackage\Service\Container;
use Bitrix\Main\AccessDeniedException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use CMain;

/**
 * @class BaseAdminPage
 * @package ANZ\Bitrix24\BasicPackage\Admin\Page
 */
abstract class BaseAdminPage implements IPage
{
    protected CMain $globalApp;
    protected string $pageTitle = 'Admin page';

    public function __construct()
    {
        $this->globalApp = ($GLOBALS['APPLICATION'] instanceof CMain) ? $GLOBALS['APPLICATION'] : new CMain;
    }

    abstract public function draw();

    /**
     * @return bool
     */
    public function isAdminPage(): bool
    {
        return true;
    }

    /**
     * @return bool
     * @throws \Exception|\Psr\Container\NotFoundExceptionInterface
     */
    public function checkAccess(): bool
    {
        if (!Loader::includeModule(Configuration::getInstance()->getBasicModuleId()))
        {
            throw new LoaderException('Basic project module not loaded');
        }

        if (!Container::getInstance()->getUserPermissions()->canViewPage($this))
        {
            throw new AccessDeniedException();
        }

        return true;
    }
}