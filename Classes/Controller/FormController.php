<?php

namespace TRAW\Powermailcaptcha\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Extbase\Object\Exception;
use TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException;
use TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException;

class FormController extends \In2code\Powermail\Controller\FormController
{
    /**
     * @throws Exception
     * @throws AspectNotFoundException
     * @throws InvalidSlotReturnException
     * @throws InvalidSlotException
     */
    public function formAction(): ResponseInterface
    {
        if ($this->settings['powermailcaptcha']['useSiteLanguage'] ?? 0) {
            $this->view->assign('languageIso', $GLOBALS['TYPO3_REQUEST']->getAttribute('language')->getTwoLetterIsoCode());
        }

        return parent::formAction();
    }
}