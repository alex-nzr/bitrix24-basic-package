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
use ANZ\Bitrix24\BasicPackage\Internal\Contract\Option\IOptionStorage;
use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @class BaseOptionPage
 * @package ANZ\Bitrix24\BasicPackage\Admin\Page\Option
 */
abstract class BaseOptionPage extends BaseAdminPage
{
    protected IOptionStorage $optionStorage;

    /**
     * BaseOptionPage constructor
     * @param \ANZ\Bitrix24\BasicPackage\Internal\Contract\Option\IOptionStorage $optionStorage
     */
    public function __construct(IOptionStorage $optionStorage)
    {
        parent::__construct();
        $this->optionStorage = $optionStorage;
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
                $this->globalApp->IncludeComponent('anz:admin.options', '', [
                    'PAGE_TITLE' => $this->pageTitle,
                    'TABS' => $this->optionStorage->getTabs()
                ]);
            }
        }
        catch (Exception | NotFoundExceptionInterface $e)
        {
            ShowError($e->getMessage());
        }
    }
}