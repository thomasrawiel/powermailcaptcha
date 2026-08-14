<?php
declare(strict_types=1);

namespace TRAW\Powermailcaptcha\Events;

final class ExpectedResponseEvent
{
    private bool $response = false;

    public function __construct(private array $configuration, private \stdClass $result) {}

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getResult(): \stdClass
    {
        return $this->result;
    }

    public function setResult(\stdClass $result): void
    {
        $this->result = $result;
    }

    public function getResponse(): bool
    {
        return $this->response;
    }

    public function setResponse(bool $response): void
    {
        $this->response = $response;
    }
}
