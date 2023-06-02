<?php
defined('TYPO3') or die('Access denied.');

$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class
);
$signalSlotDispatcher->connect(
    \In2code\Powermail\Controller\FormController::class,
    'formActionBeforeRenderView',
    \TRAW\Powermailcaptcha\Slot\FormControllerSlot::class,
    'formActionBeforeRenderView',
    FALSE
);
