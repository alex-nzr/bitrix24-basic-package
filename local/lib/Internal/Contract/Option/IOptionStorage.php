<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - IOptionStorage.php
 * 31.12.2023 01:49
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Internal\Contract\Option;

/**
 * @interface IOptionStorage
 * @package ANZ\Bitrix24\BasicPackage\Internal\Contract\Option
 */
interface IOptionStorage
{
    const OPTION_TYPE_FILE_POSTFIX = '_FILE';

    public function getTabs(): array;
}