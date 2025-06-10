<?php

namespace Qup\Interfaces;

interface Container
{
    public function register(string $className, callable $definition): object;

    public function singleton(string $className, callable $definition): object;

    /**
     * @template TClassName
     * @param class-string<TClassName> $className
     * @return TClassName
     */
    public function get(string $className): object;
}