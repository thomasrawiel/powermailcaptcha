<?php

defined('TYPO3') or die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\In2code\Powermail\Controller\FormController::class] = [
    'className' => \TRAW\Powermailcaptcha\Controller\FormController::class,
];
