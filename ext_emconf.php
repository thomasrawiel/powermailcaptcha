<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Powermail Captcha',
    'description' => 'Captcha Extension for Powermail',
    'category' => 'plugin',
    'author' => 'Thomas Rawiel',
    'author_email' => 'thomas.rawiel@gmail.com',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.5.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-12.4.99',
            'powermail' => '9.0.0-12.99.99',
        ],
        'conflicts' => [
            'powermailrecaptcha' => '',
        ],
        'suggests' => [],
    ],
];
