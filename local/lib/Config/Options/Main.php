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
    const OPTION_KEY_SOME_FILE_OPTION = 'SOME_FILE_OPTION' . parent::OPTION_TYPE_FILE_POSTFIX;

    /**
     * @return void
     */
    protected function setTabs(): void
    {
        $this->tabs = [
            [
                'DIV'   => 'string_settings_tab',
                'TAB'   => 'String settings',
                'ICON'  => '',
                'TITLE' => 'String settings',
                "OPTIONS" => [
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
                "OPTIONS" => [
                    'File settings',
                    [
                        static::OPTION_KEY_SOME_FILE_OPTION,
                        'Some file option',
                        "",
                        ['file']
                    ],
                    [ 'note' => 'Some note in file-option page'],
                ]
            ]
        ];
    }
}