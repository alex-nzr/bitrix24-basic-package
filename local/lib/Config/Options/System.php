<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - System.php
 * 22.12.2023 20:48
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Config\Options;

use ANZ\Bitrix24\BasicPackage\Internal\Contract\Option\IOptionStorage;

/**
 * Hidden from public access system options
 * @class System
 * @package ANZ\Bitrix24\BasicPackage\Config\Options
 */
class System implements IOptionStorage
{
    const OPTION_KEY_LAST_UPDATED_CONDITIONS_HASH = 'project_last_updated_conditions_hash';
    const OPTION_KEY_LAST_UPDATED_AGENTS_HASH = 'project_last_updated_agents_hash';

    /**
     * @return array
     */
    public function getTabs(): array
    {
        return [];
    }
}