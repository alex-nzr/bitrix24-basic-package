<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Storage.php
 * 31.12.2023 18:56
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Admin\Menu;

use ANZ\Bitrix24\BasicPackage\Config\Configuration;

/**
 * @class Storage
 * @package ANZ\Bitrix24\BasicPackage\Admin\Menu
 */
class Storage
{
    protected array $additionalMenuStructure;
    protected string $moduleId;

    public function __construct()
    {
        $this->moduleId = Configuration::getInstance()->getBasicModuleId();
        $this->setAdditionalMenuStructure();
    }

    /**
     * @return void
     */
    protected function setAdditionalMenuStructure(): void
    {
        $this->additionalMenuStructure = [
            [
                'ID'    => 'project_root_1',
                'TITLE' => 'Project menu',
                'ITEMS' => [
                    [
                        'TITLE' => 'Settings section',
                        'ICON' => 'sys_menu_icon',
                        'ITEMS' => [
                            [
                                'TITLE' => 'Main settings',
                                'ICON' => 'sale_menu_icon',
                                'URL' => '/bitrix/admin/main-settings-page.php?lang=' . urlencode(LANGUAGE_ID),
                            ]
                        ]
                    ],
                    [
                        'TITLE' => 'Empty menu',
                        'ICON' => 'some_icon_class',
                        'ITEMS' => []
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getAdditionalMenuStructure(): array
    {
        return $this->additionalMenuStructure;
    }
}