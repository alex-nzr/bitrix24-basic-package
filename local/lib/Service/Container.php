<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Container.php
 * 17.12.2023 02:19
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Service;

use ANZ\Bitrix24\BasicPackage\Service\Access\UserPermissions;
use Bitrix\Main\DI\ServiceLocator;

/**
 * @class Container
 * @package ANZ\Bitrix24\BasicPackage\Service
 */
class Container extends \Bitrix\Crm\Service\Container
{
    /**
     * @return static
     * @throws \Exception
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function getInstance(): static
    {
        return ServiceLocator::getInstance()->get('crm.service.container');
    }

    /**
     * @param int|null $userId
     * @return \ANZ\Bitrix24\BasicPackage\Service\Access\UserPermissions
     * @throws \Exception
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getUserPermissions(?int $userId = null): UserPermissions
    {
        if($userId === null)
        {
            $userId = $this->getContext()->getUserId();
        }

        $identifier = static::getIdentifierByClassName(UserPermissions::class, [$userId]);

        if(!ServiceLocator::getInstance()->has($identifier))
        {
            $userPermissions = new UserPermissions($userId);
            ServiceLocator::getInstance()->addInstance($identifier, $userPermissions);
        }

        return ServiceLocator::getInstance()->get($identifier);
    }
}