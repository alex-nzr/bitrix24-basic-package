<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/index.bundle.css',
	'js' => 'dist/index.bundle.js',
	'rel' => [],
	'skip_core' => false,
    'settings'  => [
        'param1' => 'value1'
    ],
    'lang' => ['lang/ru/lang.php'],
];