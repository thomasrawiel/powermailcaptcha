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
 *
 * Documentation: How to listen to events
 * @see https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Events/EventDispatcher/Index.html
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
            /** !!!! this method does not exist yet
             * see https://github.com/in2code-de/powermail/pull/1199
             */
            if (method_exists($event, 'addViewVariables')) {
                $event->addViewVariables([
                        'languageIso' => $GLOBALS['TYPO3_REQUEST']->getAttribute('language')->getLocale()->getLanguageCode()
                    ]
                );
            }
        }
    }
}
