<?php

namespace TRAW\Powermailcaptcha\Events;

/**
 * Class BeforeVerifyRequestEvent
 */
final class BeforeVerifyRequestEvent
{
    /**
     * @param string $siteVerifyUri
     * @param string $verifyMethod
     * @param array  $additionalOptions
     */
    public function __construct(private string $siteVerifyUri, private string $verifyMethod, private array $additionalOptions = [])
    {
    }

    /**
     * @return array
     */
    public function getAdditionalOptions(): array
    {
        return $this->additionalOptions;
    }

    /**
     * @return string
     */
    public function getSiteVerifyUri(): string
    {
        return $this->siteVerifyUri;
    }

    /**
     * @return string
     */
    public function getVerifyMethod(): string
    {
        return $this->verifyMethod;
    }

    /**
     * @param array $additionalOptions
     *
     * @return void
     */
    public function setAdditionalOptions(array $additionalOptions): void
    {
        $this->additionalOptions = $additionalOptions;
    }

    /**
     * @param string $siteVerifyUri
     *
     * @return void
     */
    public function setSiteVerifyUri(string $siteVerifyUri): void
    {
        $this->siteVerifyUri = $siteVerifyUri;
    }

    /**
     * @param string $verifyMethod
     *
     * @return void
     */
    public function setVerifyMethod(string $verifyMethod): void
    {
        $this->verifyMethod = $verifyMethod;
    }
}