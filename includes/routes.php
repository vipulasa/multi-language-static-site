<?php

defined('SYSTEM_ACTIVE') or die('NOW NOW, GO HACK SOMETHING ELSE');

/**
 * IMPORTANT !!, Create title elements for all languages which defined in the config php file
 */

$__ROUTER = [
    [
        'url' => '/home',
        'title' => [
            'en' => 'Home',
            'fr' => 'Home',
            'nl' => 'Home',
        ],
        'template' => 'home.template.php',
        'default' => true
    ],
    [
        'url' => '/about',
        'title' => [
            'en' => 'about',
            'fr' => 'about',
            'nl' => 'about',
        ],
        'template' => 'about.template.php',
        'default' => false
    ],
    404 => [
        'url' => false,
        'title' => [
            'en' => 'Page not found',
            'fr' => 'Page not found',
            'nl' => 'Page not found',
        ],
        'template' => '404.template.php',
        'default' => false
    ]
];