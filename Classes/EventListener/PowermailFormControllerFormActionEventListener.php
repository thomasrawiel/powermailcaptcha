<?php

declare(strict_types=1);
namespace TRAW\Powermailcaptcha\EventListener;

use In2code\Powermail\Events\FormControllerFormActionEvent;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * >= v12 only
 *
 * Listens to event FormControllerFormActionEvent
 * @see \In2code\Powermail\Events\FormControllerFormActionEvent
 */
final class PowermailFormControllerFormActionEventListener implements SingletonInterface
{
    public function handleEvent(FormControllerFormActionEvent $event): void
    {
        $form = $event->getForm();
        if ($form === null) {
            return;
        }
        $formController = $event->getFormController();
        $settings = $formController->getSettings();

        if ($settings['powermailcaptcha']['useSiteLanguage'] ?? 0) {
            if (method_exists($event, 'addViewVariables')) {
                $event->addViewVariables([
                        'languageIso' => $GLOBALS['TYPO3_REQUEST']->getAttribute('language')->getLocale()->getLanguageCode()
                    ]
                );
            }
        }
    }
}