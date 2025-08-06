<?php

namespace TRAW\Powermailcaptcha\Events;

/**
 * Class BeforeVerifyRequestEvent
 */
final class BeforeVerifyRequestEvent
{
    public function __construct(private string $siteVerifyUri, private string $verifyMethod, private array $additionalOptions = []) {}

    public function getAdditionalOptions(): array
    {
        return $this->additionalOptions;
    }

    public function getSiteVerifyUri(): string
    {
        return $this->siteVerifyUri;
    }

    public function getVerifyMethod(): string
    {
        return $this->verifyMethod;
    }

    public function setAdditionalOptions(array $additionalOptions): void
    {
        $this->additionalOptions = $additionalOptions;
    }

    public function setSiteVerifyUri(string $siteVerifyUri): void
    {
        $this->siteVerifyUri = $siteVerifyUri;
    }

    public function setVerifyMethod(string $verifyMethod): void
    {
        $this->verifyMethod = $verifyMethod;
    }
}
