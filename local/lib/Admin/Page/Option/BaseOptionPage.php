<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - BaseOptionPage.php
 * 28.12.2023 13:55
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Admin\Page\Option;

use ANZ\Bitrix24\BasicPackage\Admin\Page\BaseAdminPage;
use ANZ\Bitrix24\BasicPackage\Internal\Option\OptionManager;
use ANZ\Bitrix24\BasicPackage\Service\Container;
use Bitrix\Main\Loader;
use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @class BaseOptionPage
 * @package ANZ\Bitrix24\BasicPackage\Admin\Page\Option
 */
abstract class BaseOptionPage extends BaseAdminPage
{
    protected OptionManager $optionManager;

    /**
     * BaseOptionPage constructor
     * @param \ANZ\Bitrix24\BasicPackage\Internal\Option\OptionManager $optionManager
     */
    public function __construct(OptionManager $optionManager)
    {
        parent::__construct();
        $this->optionManager = $optionManager;
        $this->optionManager->processRequest();
    }

    /**
     * @return bool
     * @throws \Exception|\Psr\Container\NotFoundExceptionInterface
     */
    public function checkAccess(): bool
    {
        if (!Loader::includeModule($this->optionManager->getModuleId()))
        {
            throw new Exception('Basic project module not loaded');
        }

        if (!Container::getInstance()->getUserPermissions()->canViewPage($this))
        {
            throw new Exception('Access denied');
        }

        return true;
    }

    /**
     * @return void
     */
    public function draw(): void
    {
        try
        {
            if ($this->checkAccess())
            {
                $this->optionManager->drawHtml();
            }
        }
        catch (Exception | NotFoundExceptionInterface $e)
        {
            ShowError($e->getMessage());
        }
    }
}