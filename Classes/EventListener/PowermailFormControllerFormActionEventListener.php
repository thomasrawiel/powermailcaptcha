<?php

declare(strict_types=1);
namespace TRAW\Powermailcaptcha\EventListener;

use In2code\Powermail\Controller\AbstractController;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Events\FormControllerFormActionEvent;
use In2code\Powermail\Utility\FrontendUtility;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
    public function __invoke(FormControllerFormActionEvent $event): void
    {
        $form = $event->getForm();
        if ($form === null) {
            return;
        }
        $formController = $event->getFormController();
        $settings = $formController->getSettings();

        if ($settings['powermailcaptcha']['useSiteLanguage'] ?? 0) {
            // !!!! this method does not exist
            $this->event->assignView('languageIso', $GLOBALS['TYPO3_REQUEST']->getAttribute('language')->getLocale()->getLanguageCode());
        }
    }
}
