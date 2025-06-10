<?php

namespace Qup\Interfaces;

use Qup\Http\Status;

interface Response
{
    public function getStatus(): Status;

    public function getBody(): string;
}