<?php
declare(strict_types=1);

namespace TRAW\Powermailcaptcha\Events;

use Psr\Http\Message\RequestInterface;

final class ExpectedResponseEvent
{
    private bool $response = false;

    public function __construct(private readonly array $configuration, private readonly \stdClass $result, private readonly ?RequestInterface $request)
    {
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function getResult(): \stdClass
    {
        return $this->result;
    }

    public function getResponse(): bool
    {
        return $this->response;
    }

    public function setResponse(bool $response): void
    {
        $this->response = $response;
    }

    public function getRequest(): ?RequestInterface
    {
        return $this->request;
    }
}
