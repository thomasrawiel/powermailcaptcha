<?php
declare(strict_types=1);

namespace TRAW\Powermailcaptcha\Events;

class CaptchaConfigurationEvent
{
    public function __construct(private array $captchaConfiguration) {}

    public function getCaptchaConfiguration(): array
    {
        return $this->captchaConfiguration;
    }

    public function setCaptchaConfiguration(array $captchaConfiguration): void
    {
        $this->captchaConfiguration = $captchaConfiguration;
    }
}
