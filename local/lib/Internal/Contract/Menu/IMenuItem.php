<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - IMenuItem.php
 * 31.12.2023 19:10
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Internal\Contract\Menu;

/**
 * @interface IMenuItem
 * @package ANZ\Bitrix24\BasicPackage\Internal\Contract\Menu
 */
interface IMenuItem
{
    public static function fromArray(array $data): static;
    public function isParent(): bool;
    public function getCompatibleData(): array;
}