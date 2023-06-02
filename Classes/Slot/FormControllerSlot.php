<?php
declare(strict_types=1);

namespace TRAW\Powermailcaptcha\Slot;

use In2code\Powermail\Controller\FormController;
use In2code\Powermail\Domain\Model\Form;

class FormControllerSlot
{

    public function formActionBeforeRenderView(Form $form, FormController $formController)
    {
        $settings = $formController->getSettings();
        if ($settings['powermailcaptcha']['useSiteLanguage'] ?? 0) {
            $formController->assignView('languageIso', $GLOBALS['TYPO3_REQUEST']->getAttribute('language')->getTwoLetterIsoCode());
        }
    }
}
