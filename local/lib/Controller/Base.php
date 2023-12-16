<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Base.php
 * 15.12.2023 21:55
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Controller;

use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Engine\Controller;

/**
 * @class Base
 * @package ANZ\Bitrix24\BasicPackage\Controller
 */
abstract class Base extends Controller
{
    /**
     * @return array
     */
    protected function getDefaultPreFilters(): array
    {
        return [
            new HttpMethod([HttpMethod::METHOD_POST]),
            new Csrf(),
            new Authentication()
        ];
    }

    /**
     * @return array
     */
    public function configureActions(): array
    {
        return [];
    }
}