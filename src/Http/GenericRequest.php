<?php

namespace Qup\Http;

use Qup\Interfaces\Request;

final readonly class GenericRequest implements Request
{
    public function __construct(
        private Method $method,
        private string $uri,
        private array $body,
    ) {}

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getBody(): array
    {
        return $this->body;
    }
}