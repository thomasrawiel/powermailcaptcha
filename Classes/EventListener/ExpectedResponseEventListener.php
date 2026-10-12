<?php
declare(strict_types=1);

namespace TRAW\Powermailcaptcha\EventListener;

use Psr\Http\Message\RequestInterface;
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
            $response = $this->evaluateProcatpchaResult($result);
        }

        if (($configuration['captchaMethod'] ?? null) === 'recaptcha' && $response) {
            $evaluation = $this->evaluateRecaptcha2Result($result, $configuration, $event->getRequest());

            if(!$evaluation) {
                $response = false;
            }
        }

        if (($configuration['captchaMethod'] ?? null) === 'recaptcha3' && $response) {
            $evaluation = $this->evaluateRecaptcha3Result($result, $configuration, $event->getRequest());

            if (!$evaluation) {
                $response = false;
            }
        }

        $event->setResponse($response);
    }

    private function evaluateProcatpchaResult(\stdClass $result): bool
    {
        return ($result->status ?? null) === 'ok'
            && ($result->verified ?? false);
    }

    private function evaluateRecaptcha2Result(\stdClass $result, array $configuration, ?RequestInterface $request): bool {
        $hostnameEvaluation = false;
        if ($request !== null) {
            $hostName = $result->hostname ?? null;
            try {
                $requestHostName = $request->getAttribute('normalizedParams')->getHttpHost();
                $hostnameEvaluation = $hostName === $requestHostName;
            } catch (\Throwable) {
            }
        }

        return $hostnameEvaluation;
    }

    private function evaluateRecaptcha3Result(\stdClass $result, array $configuration, ?RequestInterface $request): bool
    {
        $minScore = $configuration['minScore'] ?? null;
        $score = $result->score ?? null;
        $scoreEvaluation = is_numeric($minScore) && (float)$minScore >= 0 && (float)$minScore <= 1
            && is_numeric($score) && (float)$score >= 0 && (float)$score <= 1
            && (float)$score >= (float)$minScore;

        $hostnameEvaluation = false;
        if ($request !== null) {
            $hostName = $result->hostname ?? null;
            try {
                $requestHostName = $request->getAttribute('normalizedParams')->getHttpHost();
                $hostnameEvaluation = $hostName === $requestHostName;
            } catch (\Throwable) {
            }
        }

        $action = $result->action ?? null;
        $expectedAction = $configuration['action'] ?? null;

        $actionEvaluation = is_string($expectedAction)
            && $expectedAction !== ''
            && is_string($action)
            && $action === $expectedAction;

        return $scoreEvaluation && $hostnameEvaluation && $actionEvaluation;
    }
}
