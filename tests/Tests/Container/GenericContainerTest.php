<?php

namespace Tests\Container;

use PHPUnit\Framework\TestCase;
use Qup\Container\GenericContainer;

class GenericContainerTest extends TestCase
{
    public function testInstance(): void
    {
        $container = new GenericContainer();

        $container->register(DummyClass::class, fn() => new DummyClass());

        $this->assertInstanceOf(DummyClass::class, $container->get(DummyClass::class));
    }

    public function testInstanceAutowire(): void
    {
        $container = new GenericContainer();

        $dependent = $container->get(DependentClass::class);

        $this->assertInstanceOf(DependentClass::class, $dependent);
    }

    public function testInstanceSingleton(): void
    {
        $container = new GenericContainer();

        $container->singleton(SingletonClass::class, fn() => new SingletonClass());

        $singleton = $container->get(SingletonClass::class);
        $this->assertInstanceOf(SingletonClass::class, $singleton);
        $this->assertEquals(1, $singleton->counter);

        $singleton = $container->get(SingletonClass::class);
        $this->assertInstanceOf(SingletonClass::class, $singleton);
        $this->assertEquals(1, $singleton->counter);
    }
}

class DummyClass {

}

class DependentClass {
    public function __construct(private DummyClass $dummy) {}
}

class SingletonClass {
    public int $counter = 0;

    public function __construct() {
        $this->counter++;
    }
}

