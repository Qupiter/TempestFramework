<?php

namespace Qup\Interfaces;

use Qup\Http\Method;

interface Request
{
    public function getMethod(): Method;

    public function getUri(): string;

    public function getBody(): array;
}