<?php

defined('TYPO3') or die('Access denied.');
// Add Page TSConfig
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:powermailcaptcha/Configuration/PageTsConfig/Powermailcaptcha.typoscript">'
);
