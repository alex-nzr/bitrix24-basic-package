<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Main.php
 * 17.12.2023 03:55
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Config\Options;

use ANZ\Bitrix24\BasicPackage\Internal\Contract\Option\IOptionStorage;
use ANZ\Bitrix24\BasicPackage\Provider\UI\EntitySelector\AdminProvider;

/**
 * @class Main
 * @package ANZ\Bitrix24\BasicPackage\Config\Options
 */
class Main implements IOptionStorage
{
    const OPTION_KEY_SOME_TEXT_OPTION = 'project_some_text_option';
    const OPTION_KEY_SOME_FILE_OPTION = 'project_some_option' . self::OPTION_TYPE_FILE_POSTFIX;
    const OPTION_KEY_ADMIN_SELECTOR_OPTION = 'project_admin_selector_option';

    /**
     * @return array
     */
    public function getTabs(): array
    {
        return [
            [
                'DIV'   => 'string_settings_tab',
                'TAB'   => 'String settings',
                'ICON'  => '',
                'TITLE' => 'String settings',
                'OPTIONS' => [
                    'String settings',
                    [
                        static::OPTION_KEY_SOME_TEXT_OPTION,
                        'Some text option',
                        "placeholder value",
                        ['text', 50]
                    ],
                    [ 'note' => 'Some note in string-option page'],
                ]
            ],
            [
                'DIV'   => 'file_settings_tab',
                'TAB'   => 'File settings',
                'ICON'  => '',
                'TITLE' => 'File settings',
                'OPTIONS' => [
                    'File settings',
                    [
                        static::OPTION_KEY_SOME_FILE_OPTION,
                        'Some file option',
                        "",
                        ['file']
                    ],
                    [ 'note' => 'Some note in file-option page'],
                ]
            ],
            [
                'DIV'   => 'ui_selector_settings_tab',
                'TAB'   => 'UI selector settings',
                'ICON'  => '',
                'TITLE' => 'UI selector settings',
                'OPTIONS' => [
                    'UI selector settings',
                    [
                        static::OPTION_KEY_ADMIN_SELECTOR_OPTION,
                        'Some ui-selector option',
                        json_encode([]),
                        [
                            'ui-selector',             //option type
                            [                          //entity. Multiple entities not supported
                                'id' => AdminProvider::ENTITY_ID,
                                'options' => null
                            ],
                            'Y',                       //multiple Y/N
                            [                          //eventHandlers to tagSelector. In BX namespace required!
                                'onAfterTagAdd' => [
                                    'namespace' => 'BX.Anz.Admin.UI.Options',
                                    'method' => 'testEventHandler'
                                ]
                            ]
                        ]
                    ],
                    [ 'note' => 'Some note in ui-selector-option page'],
                ]
            ],
        ];
    }
}