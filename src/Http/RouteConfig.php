<?php

namespace Qup\Http;

final readonly class RouteConfig
{
    public function __construct(public array $controllers = []){}
}