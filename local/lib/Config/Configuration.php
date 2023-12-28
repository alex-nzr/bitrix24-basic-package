<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Configuration.php
 * 16.12.2023 22:28
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Config;

use ANZ\Bitrix24\BasicPackage\Internal\Traits\Singleton;

/**
 * @class Configuration
 * @package ANZ\Bitrix24\BasicPackage\Config
 */
class Configuration
{
    use Singleton;

    /**
     * @return string
     */
    public function getBasicModuleId(): string
    {
        return Constants::BASIC_MODULE_ID;
    }

    /**
     * При изменении массива правил, обновление urlrewrite.php произойдёт автоматически
     * см. \ANZ\Bitrix24\BasicPackage\Internal\ServiceManager::updateUrlRewriter()
     * @return array[]
     */
    public function getUrlRewriteConditions(): array
    {
        return [
            [
                'CONDITION' => '#^/bitrix/admin/main-settings-page.php#',
                'RULE' => '',
                'ID' => null,
                'PATH' => '/local/admin/pages/main-settings-page.php',
            ]
        ];
    }

    /**
     * @return string
     */
    public function getUrlRewriteConditionsHash(): string
    {
        return hash('sha512', json_encode($this->getUrlRewriteConditions()));
    }
}