<?php

namespace Qup\Http;

use Qup\Interfaces\Response;

final readonly class ErrorResponse implements Response
{
    private Status $status;

    private string $body;

    public function __construct() {
        $this->status = Status::HTTP_404;
        $this->body = 'Not Found';
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}