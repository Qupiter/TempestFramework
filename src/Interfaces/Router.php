<?php

namespace Qup\Interfaces;

interface Router
{
    public function dispatch(Request $request): Response;
}