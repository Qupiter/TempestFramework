<?php

namespace Qup\Http;
use Attribute;

#[Attribute]
readonly class Route
{
    public function __construct(public string $uri, public Method $method) {}
}