<?php
declare(strict_types=1);

namespace TRAW\Powermailcaptcha\EventListener;

use TRAW\Powermailcaptcha\Events\ExpectedResponseEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;

#[AsEventListener(identifier: 'powermailcaptchaExpectedResponse')]
final class ExpectedResponseEventListener
{
    public function __invoke(ExpectedResponseEvent $event)
    {
        $configuration = $event->getConfiguration();
        $result = $event->getResult();

        $response = (bool)($result->success ?? false);

        if (($configuration['captchaMethod'] ?? null) === 'procaptcha') {
            $response = ($result->status ?? null) === 'ok'
                && ($result->verified ?? false);
        }

        $event->setResponse($response);
    }
}
