<?php

declare(strict_types=1);

namespace TRAW\Powermailcaptcha\Domain\Validator\SpamShield;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Validator\SpamShield\AbstractMethod;
use Psr\Http\Message\RequestInterface;
use TRAW\Powermailcaptcha\Events\BeforeVerifyRequestEvent;
use TRAW\Powermailcaptcha\Events\CaptchaConfigurationEvent;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\Exception;

/**
 * Class CaptchaMethod
 */
class CaptchaMethod extends AbstractMethod
{
    protected string $secretKey = '';

    protected string $captchaMethod = '';

    protected array $captchaConfiguration = [];

    protected EventDispatcher $eventDispatcher;

    /**
     * @var array|array[]
     */
    protected const array defaultCaptchaConfiguration = [
        'recaptcha' => [
            'siteVerifyUri' => 'https://www.google.com/recaptcha/api/siteverify',
            'verifyMethod' => 'GET',
            'responseKey' => 'g-recaptcha-response',
            'secretParameter' => 'secret',
            'responseParameter' => 'response',
        ],
        'friendlycaptcha' => [
            'siteVerifyUri' => [
                'default' => 'https://api.friendlycaptcha.com/api/v1/siteverify',
                'eu' => 'https://eu-api.friendlycaptcha.eu/api/v1/siteverify',
            ],
            'verifyMethod' => 'POST',
            'responseKey' => 'frc-captcha-solution',
            'secretParameter' => 'secret',
            'responseParameter' => 'solution',
        ],
        'friendlycaptcha2' => [
            'siteVerifyUri' => [
                'default' => 'https://global.frcapi.com/api/v2/captcha/siteverify',
                'eu' => 'https://eu.frcapi.com/api/v2/captcha/siteverify',
            ],
            'verifyMethod' => 'POST',
            'responseKey' => 'frc-captcha-response',
            'secretParameter' => null, //intentionally null
            'responseParameter' => 'response',
            'additionalOptions' => [
                'headers' => ['X-API-Key' => '###SECRET_KEY###'],
            ],
        ],
        'hcaptcha' => [
            'siteVerifyUri' => 'https://hcaptcha.com/siteverify',
            'verifyMethod' => 'POST',
            'responseKey' => 'h-captcha-response',
            'secretParameter' => 'secret',
            'responseParameter' => 'response',
        ],
        'cloudflare' => [
            'siteVerifyUri' => 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            'verifyMethod' => 'POST',
            'responseKey' => 'cf-turnstile-response',
            'secretParameter' => 'secret',
            'responseParameter' => 'response',
        ],
        'procaptcha' => [
            'siteVerifyUri' => 'https://api.prosopo.io/siteverify',
            'verifyMethod' => 'POST',
            'responseKey' => 'procaptcha-response',
            'secretParameter' => 'secret',
            'responseParameter' => 'token',
        ],
    ];

    /**
     * Check if secret key is given and set it
     *
     * @throws \Exception
     */
    public function initialize(): void
    {
        $this->eventDispatcher = GeneralUtility::makeInstance(EventDispatcher::class);
        $this->captchaMethod = $this->configuration['captchaMethod'];

        if ($this->isFormWithCaptchaField()) {
            if (empty($this->configuration['secretkey']) || $this->configuration['secretkey'] === 'abcdef') {
                throw new \LogicException(
                    'No secretkey given. Please add a secret key to TypoScript Constants',
                    1607012762
                );
            }

            $this->secretKey = $this->configuration['secretkey'];
        }

        $captchaConfigration = $this->eventDispatcher->dispatch(
            new CaptchaConfigurationEvent(self::defaultCaptchaConfiguration)
        )->getCaptchaConfiguration();

        $this->captchaConfiguration = $captchaConfigration[$this->captchaMethod] ?? [];

        if ($this->captchaConfiguration === []) {
            throw new \LogicException(
                sprintf('Unsupported captcha method "%s".', $this->captchaMethod)
            );
        }
    }

    /**
     * @return bool true if spam recognized
     */
    #[\Override]
    public function spamCheck(): bool
    {
        if (!$this->isFormWithCaptchaField() || $this->isCaptchaCheckToSkip()) {
            return false;
        }

        if ($this->getCaptchaResponse() !== '') {
            return !$this->verifyCaptchaResponse();
        }

        return true;
    }

    protected function verifyCaptchaResponse(): bool
    {
        $additionalOptions = [
            'headers' => ['Cache-Control' => 'no-cache'],
            'allow_redirects' => false,
        ];

        $urlParameters = [];

        $responseParameter = $this->captchaConfiguration['responseParameter'] ?? false;
        if ((bool)$responseParameter) {
            $urlParameters[$responseParameter] = $this->getCaptchaResponse();
        }

        $secretParameter = $this->captchaConfiguration['secretParameter'] ?? null;
        if ((bool)$secretParameter) {
            $urlParameters[$secretParameter] = $this->secretKey;
        }

        $siteVerifyUri = $this->getSiteVerifyUri();

        if ($this->captchaConfiguration['verifyMethod'] === 'GET') {
            $siteVerifyUri = $siteVerifyUri . '?' . http_build_query($urlParameters);
        }

        if ($this->captchaConfiguration['verifyMethod'] === 'POST') {
            $additionalOptions['form_params'] = $urlParameters;
        }

        if ($this->captchaConfiguration['additionalOptions'] ?? false) {
            $additionalOptions = array_replace_recursive($additionalOptions, $this->captchaConfiguration['additionalOptions']);
        }

        /** @var BeforeVerifyRequestEvent $event */
        $event = $this->eventDispatcher->dispatch(
            new BeforeVerifyRequestEvent(
                $siteVerifyUri,
                $this->captchaConfiguration['verifyMethod'],
                $additionalOptions
            )
        );

        $additionalOptions = $this->replacePlaceHolders($event->getAdditionalOptions());

        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
        $jsonResult = $requestFactory->request(
            $event->getSiteVerifyUri(),
            $event->getVerifyMethod(),
            $additionalOptions
        )->getBody()->getContents();

        $result = \json_decode($jsonResult);

        return $this->getExpectedResponseAttribute($result);
    }

    /**
     * Check if current form has a recaptcha field
     *
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws Exception
     */
    protected function isFormWithCaptchaField(): bool
    {
        foreach ($this->mail->getForm()->getPages() as $page) {
            /** @var Field $field */
            foreach ($page->getFields() as $field) {
                if ($field->getType() === 'powermailcaptcha') {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get captcha response in the current request
     */
    protected function getCaptchaResponse(): string
    {
        $request = $this->getRequest();
        $response = null;

        if ($request instanceof RequestInterface) {
            $responseKey = $this->captchaConfiguration['responseKey'] ?? '';
            $response = $request->getParsedBody()[$responseKey] ?? '';
        }

        if (!empty($response)) {
            return $response;
        }

        return '';
    }

    /**
     * Captcha check should be skipped on createAction if there was a confirmationAction where the captcha was
     * already checked before.
     * Captcha check should also be skipped on optinConfirm action if double-optin is activated in Flexform.
     *
     * Note: $this->flexForm is only available in powermail 3.9 or newer
     */
    protected function isCaptchaCheckToSkip(): bool
    {
        if (property_exists($this, 'flexForm')) {
            $action = $this->getActionName();
            $confirmationActive = ($this->flexForm['settings']['flexform']['main']['confirmation'] ?? null) === '1';
            $optinActive = ($this->flexForm['settings']['flexform']['main']['optin'] ?? null) === '1';
            if (($action === 'create' || $action === 'checkCreate') && $confirmationActive) {
                return true;
            }

            if ($action === 'optinConfirm' && $optinActive) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string "confirmation" or "create"
     */
    protected function getActionName(): string
    {
        $request = $this->getRequest();
        $pluginVariables = [];

        if ($request instanceof RequestInterface) {
            $getParams = $request->getQueryParams()['tx_powermail_pi1'] ?? [];
            $postParams = $request->getParsedBody()['tx_powermail_pi1'] ?? [];
            $pluginVariables = $getParams;

            \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($pluginVariables, $postParams);
        }

        return $pluginVariables['action'] ?? '';
    }

    protected function getExpectedResponseAttribute(array|\stdClass $result): bool
    {
        if ($this->configuration['captchaMethod'] === 'procaptcha') {
            return $result->status === 'ok' && $result->verified;
        }

        return (bool)($result->success ?? false);
    }

    protected function getRequest(): ?RequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'] ?? null;
    }

    private function replacePlaceHolders(mixed $value): mixed
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = $this->replacePlaceHolders($v);
            }
            return $value;
        }

        return is_string($value)
            ? str_replace('###SECRET_KEY###', $this->secretKey, $value)
            : $value;
    }

    private function getSiteVerifyUri(): string
    {
        $url = $this->captchaConfiguration['siteVerifyUri'];
        $endpoint = $this->configuration['endpoint'];
        
        if (is_array($url)) {
            if (!isset($url[$endpoint])) {
                throw new \LogicException('Endpoint "' . $endpoint . '" does not exist');
            }
            return $url[$endpoint];
        }

        return $url;
    }
}
