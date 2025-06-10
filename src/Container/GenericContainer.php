<?php

namespace Qup\Container;

use Qup\Interfaces\Container;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

final class GenericContainer implements Container
{
    private array $definitions = [];
    private array $singletons = [];

    public function register(string $className, callable $definition): Container
    {
        $this->definitions[$className] = $definition;
        return $this;
    }

    public function singleton(string $className, callable $definition): Container
    {
        $this->definitions[$className] = function () use ($className, $definition) {
            if (!isset($this->singletons[$className])) {
                $this->singletons[$className] = $definition($this);
            }
            return $this->singletons[$className];
        };

        return $this;
    }

    public function get(string $className): object
    {
        if (isset($this->singletons[$className])) {
            return $this->singletons[$className];
        }

        if (isset($this->definitions[$className])) {
            return $this->definitions[$className]($this);
        }

        return $this->autowire($className);
    }

    /**
     * @throws ReflectionException
     */
    private function autowire(string $className): object
    {
        $reflection = new ReflectionClass($className);

        $parameters = array_map(
            fn(ReflectionParameter $parameter) => $this->get($parameter->getType()->getName()),
            $reflection->getConstructor()?->getParameters() ?? []
        );

        return new $className(...$parameters);
    }
}