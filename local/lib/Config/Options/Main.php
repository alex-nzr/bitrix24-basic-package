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

use ANZ\Bitrix24\BasicPackage\Internal\Option\OptionManager;

/**
 * @class Main
 * @package ANZ\Bitrix24\BasicPackage\Config\Options
 */
class Main extends OptionManager
{
    const OPTION_KEY_SOME_TEXT_OPTION = 'SOME_TEXT_OPTION';
    const OPTION_KEY_SOME_FILE_OPTION = 'SOME_FILE_OPTION';
    const OPTION_KEY_SOME_COLOR_OPTION = 'SOME_COLOR_OPTION';

    /**
     * @return void
     */
    protected function setTabs(): void
    {
        $this->tabs = [
            [
                'DIV'   => "settings_tab",
                'TAB'   => 'Main settings',
                'ICON'  => '',
                'TITLE' => 'Main settings',
                "OPTIONS" => [
                    'Main settings',
                    [
                        static::OPTION_KEY_SOME_TEXT_OPTION,
                        'Some text option',
                        "placeholder value",
                        ['text', 50]
                    ],
                    [
                        static::OPTION_KEY_SOME_FILE_OPTION,
                        'Some file option',
                        "",
                        ['file']
                    ],
                    [
                        static::OPTION_KEY_SOME_COLOR_OPTION,
                        'Some color-picker option',
                        "#025ea1",
                        ['colorPicker']
                    ],
                    [ 'note' => 'Some note in option page'],
                ]
            ]
        ];
    }
}