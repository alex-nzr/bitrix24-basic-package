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

use ANZ\Bitrix24\BasicPackage\Internal\Contract\Page\IPage;
use ANZ\Bitrix24\BasicPackage\Internal\Option\OptionManager;
use Bitrix\Main\Loader;
use CMain;
use Exception;

/**
 * @class BaseOptionPage
 * @package ANZ\Bitrix24\BasicPackage\Admin\Page\Option
 */
abstract class BaseOptionPage implements IPage
{
    protected OptionManager $optionManager;
    protected CMain $globalApp;

    /**
     * BaseOptionPage constructor
     * @param \ANZ\Bitrix24\BasicPackage\Internal\Option\OptionManager $optionManager
     */
    public function __construct(OptionManager $optionManager)
    {
        $this->optionManager = $optionManager;
        $this->globalApp = ($GLOBALS['APPLICATION'] instanceof CMain) ? $GLOBALS['APPLICATION'] : new CMain;

        $this->optionManager->processRequest();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function checkAccess(): bool
    {
        if (!Loader::includeModule($this->optionManager->getModuleId()))
        {
            throw new Exception('Basic project module not loaded');
        }

        if ($this->globalApp->GetGroupRight($this->optionManager->getModuleId()) < 'W')
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
            $this->optionManager->startDrawHtml();
            $this->optionManager->endDrawHtml();
        }
        catch (Exception $e)
        {
            ShowError($e->getMessage());
        }
    }
}