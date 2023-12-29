<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Storage.php
 * 29.12.2023 20:00
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Agent;

use ANZ\Bitrix24\BasicPackage\Agent\Test\Test;

/**
 * @class Storage
 * @package ANZ\Bitrix24\BasicPackage\Agent
 */
class Storage
{
    /**
     * @return array[]
     */
    public static function getAgentsData(): array
    {
        return [
            [
                'handler'   => Test::class."::testAgent();",
                'period'    => "N",
                'interval'  => 86400,
                'userId'    => null,
                'active'    => 'Y',
                'nextExec'  => "29.12.2023 18:00:00",//не использовать time() и другие методы с постоянно меняющимся значением, так как в этом случае хэш массива будет всё время разный
                'sort'      => 100
            ],

            //USE DEL=Y to delete condition on next hit
            [
                'handler'   => Test::class."::testDelete();",
                'DEL'    => "Y"
            ]
        ];
    }

    /**
     * @return string
     */
    public static function getAgentsDataHash(): string
    {
        return hash('sha512', json_encode(static::getAgentsData()));
    }
}