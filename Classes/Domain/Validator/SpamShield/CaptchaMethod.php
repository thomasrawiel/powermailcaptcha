<?php

declare(strict_types=1);

namespace TRAW\Powermailcaptcha\Domain\Validator\SpamShield;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Validator\SpamShield\AbstractMethod;
use Psr\Http\Message\RequestInterface;
use TRAW\Powermailcaptcha\Events\BeforeVerifyRequestEvent;
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
    /**
     * @var string
     */
    protected string $secretKey = '';

    /**
     * @var string
     */
    protected string $captchaMethod = '';

    /**
     * @var array|array[]
     */
    protected array $captchaConfiguration = [
        'recaptcha' => [
            'siteVerifyUri' => 'https://www.google.com/recaptcha/api/siteverify',
            'verifyMethod' => 'GET',
            'responseKey' => 'g-recaptcha-response',
            'secretParameter' => 'secret',
            'responseParameter' => 'response',
        ],
        'friendlycaptcha' => [
            'siteVerifyUri' => 'https://api.friendlycaptcha.com/api/v1/siteverify',
            'verifyMethod' => 'POST',
            'responseKey' => 'frc-captcha-solution',
            'secretParameter' => 'secret',
            'responseParameter' => 'solution',
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
    }

    /**
     * @return bool true if spam recognized
     */
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

    /**
     * @return bool
     */
    protected function verifyCaptchaResponse(): bool
    {
        $additionalOptions = [
            'headers' => ['Cache-Control' => 'no-cache'],
            'allow_redirects' => false,
        ];

        $urlParameters = [
            $this->captchaConfiguration[$this->captchaMethod]['secretParameter'] => $this->secretKey,
            $this->captchaConfiguration[$this->captchaMethod]['responseParameter'] => $this->getCaptchaResponse(),
        ];

        $siteVerifyUri = $this->captchaConfiguration[$this->captchaMethod]['siteVerifyUri'];

        if ($this->captchaConfiguration[$this->captchaMethod]['verifyMethod'] === 'GET') {
            $siteVerifyUri = $siteVerifyUri . '?' . http_build_query($urlParameters);
        }

        if ($this->captchaConfiguration[$this->captchaMethod]['verifyMethod'] === 'POST') {
            $additionalOptions['form_params'] = $urlParameters;
        }

        /** @var BeforeVerifyRequestEvent $event */
        $event = (GeneralUtility::makeInstance(EventDispatcher::class))
            ->dispatch(new BeforeVerifyRequestEvent(
                $siteVerifyUri,
                $this->captchaConfiguration[$this->captchaMethod]['verifyMethod'],
                $additionalOptions
            ));

        $requestFactory = GeneralUtility::makeInstance(RequestFactory::class);
        $jsonResult = $requestFactory->request(
            $event->getSiteVerifyUri(),
            $event->getVerifyMethod(),
            $event->getAdditionalOptions()
        )->getBody()->getContents();

        $result = \json_decode($jsonResult);

        return $this->getExpextedResponseAttribute($result);
    }

    /**
     * Check if current form has a recaptcha field
     *
     * @return bool
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
     * @return string|false
     */
    protected function getCaptchaResponse(): string
    {
        $request = $this->getRequest();
        $response = null;

        if (!empty($request)) {
            $responseKey = $this->captchaConfiguration[$this->captchaMethod]['responseKey'] ?? '';
            $response = isset($request->getParsedBody()[$responseKey]) ? $request->getParsedBody()[$responseKey] : '';;
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
     *
     * @return bool
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

        if (!empty($request)) {
            $getParams = $request->getQueryParams()['tx_powermail_pi1'] ?? [];
            $postParams = $request->getParsedBody()['tx_powermail_pi1'] ?? [];
            $pluginVariables = $getParams;

            \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($pluginVariables, $postParams);
        }
        return $pluginVariables['action'] ?? '';
    }

    /**
     * @param array|\stdClass $result
     *
     * @return bool
     */
    protected function getExpextedResponseAttribute(array|\stdClass $result): bool
    {
        if ($this->configuration['captchaMethod'] === 'procaptcha') {
            return $result->status === 'ok' && $result->verified;
        }

        return (bool)$result->success;
    }

    /**
     * @return RequestInterface|null
     */
    protected function getRequest(): ?RequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'] ?? null;
    }
}
