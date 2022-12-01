<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Powermail Captcha',
    'description' => 'Captcha Extension for Powermail',
    'category' => 'plugin',
    'author' => 'Thomas Rawiel',
    'author_email' => 'thomas.rawiel@gmail.com',
    'state' => 'beta',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.7.99',
            'powermail' => '3.0.0-4.99.99',
        ],
        'conflicts' => [
            'powermailrecaptcha' => '',
        ],
        'suggests' => [],
    ],
];