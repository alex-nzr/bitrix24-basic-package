<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - IPage.php
 * 28.12.2023 13:56
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Internal\Contract\Page;

/**
 * @interface IPage
 * @package ANZ\Bitrix24\BasicPackage\Internal\Contract\Page
 */
interface IPage
{
    public function checkAccess();
    public function draw();
    public function isAdminPage() : bool;
}