<?php
declare(strict_types=1);

namespace TRAW\Powermailcaptcha\Events;

class CaptchaConfigurationEvent
{
    public function __construct(private array $configuration){}

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }
}