<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - MainSettingsPage.php
 * 28.12.2023 15:16
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Admin\Page\Option;

use ANZ\Bitrix24\BasicPackage\Internal\Option\OptionManager;

/**
 * @class MainSettingsPage
 * @package ANZ\Bitrix24\BasicPackage\Admin\Page\Option
 */
class MainSettingsPage extends BaseOptionPage
{
    public function __construct(OptionManager $optionManager)
    {
        parent::__construct($optionManager);
        $this->globalApp->SetTitle('Main settings');
    }
}