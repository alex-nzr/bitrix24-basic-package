<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - EventManager.php
 * 15.12.2023 21:52
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Event;

use ANZ\Bitrix24\BasicPackage\Event\Handler\Main;
use Bitrix\Main\EventManager as BitrixEventManager;

/**
 * @class EventManager
 * @package ANZ\Bitrix24\BasicPackage\Event
 */
class EventManager
{
    /**
     * @return array
     */
    public static function getEventHandlers(): array
    {
        return [
            'main' => [
                'OnBuildGlobalMenu' => [
                    [
                        'class'  => Main::class,
                        'method' => 'onBuildGlobalMenu',
                        'sort'   => 500,
                    ]
                ],
            ],
        ];
    }

    /**
     * @return void
     */
    public static function addEventHandlers(): void
    {
        foreach (static::getEventHandlers() as $moduleId => $event)
        {
            foreach ($event as $eventName => $handlers)
            {
                foreach ($handlers as $handler)
                {
                    BitrixEventManager::getInstance()->addEventHandler(
                        $moduleId,
                        $eventName,
                        [$handler['class'], $handler['method']],
                        false,
                        $handler['sort'] ?? 100
                    );
                }
            }
        }
    }
}