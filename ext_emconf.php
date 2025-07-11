<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Powermail Captcha',
    'description' => 'Captcha Extension for Powermail',
    'category' => 'plugin',
    'author' => 'Thomas Rawiel',
    'author_email' => 'thomas.rawiel@gmail.com',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '3.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
            'powermail' => '12.5.0-13.99.99',
        ],
        'conflicts' => [
            'powermailrecaptcha' => '',
        ],
        'suggests' => [],
    ],
];
