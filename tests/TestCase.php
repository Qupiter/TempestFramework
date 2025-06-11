<?php

namespace Tests;

use App\Controllers\HomeController;
use Qup\Container\GenericContainer;
use Qup\Http\GenericRouter;
use Qup\Http\RouteConfig;
use Qup\Interfaces\Container;
use Qup\Interfaces\Router;

class TestCase  extends \PHPUnit\Framework\TestCase
{
    protected GenericContainer $container;

    public function setUp(): void
    {
        $this->container = new GenericContainer();

        $this->container->singleton(Container::class, fn () => $this->container);

        $this->container->singleton(
            Router::class,
            fn (Container $container) => $container->get(GenericRouter::class)
        );

        $this->container->singleton(
            RouteConfig::class,
            fn () => new RouteConfig(
            controllers: [
                HomeController::class
            ]
        ));
    }
}