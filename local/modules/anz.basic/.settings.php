<?php

use ANZ\Bitrix24\BasicPackage\Config\Configuration;
use ANZ\Bitrix24\BasicPackage\Provider\UI\EntitySelector\ExampleProvider;

return [
    'controllers'        => [
        'value'    => [
            'defaultNamespace' => '\\ANZ\\Bitrix24\\BasicPackage\\Controller',
        ],
        'readonly' => true,
    ],
    'ui.entity-selector' => [
        'value'    => [
            'entities'   => [
                [
                    'entityId' => ExampleProvider::ENTITY_ID,
                    'provider' => [
                        'moduleId'  => Configuration::getInstance()->getBasicModuleId(),
                        'className' => ExampleProvider::class,
                    ],
                ],
            ],
            'extensions' => [],
        ],
        'readonly' => true,
    ],
];
