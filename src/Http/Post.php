<?php

namespace Qup\Http;

use Attribute;
use Qup\Http\Route;

#[Attribute]
final readonly class Post extends Route
{
    public function __construct(string $uri) {
        parent::__construct($uri, Method::POST);
    }
}