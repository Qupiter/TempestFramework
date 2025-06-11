<?php

namespace Tests\Swagger;

use App\Controllers\HomeController;
use App\Controllers\ProductController;
use Qup\Container\GenericContainer;
use Qup\Http\RouteConfig;
use Qup\Interfaces\Container;
use Qup\Interfaces\Router;
use Qup\Swagger\RouteReader;
use PHPUnit\Framework\TestCase;

class RouteReaderTest extends TestCase
{
    protected GenericContainer $container;

    public function setUp(): void
    {
        $this->container = new GenericContainer();

        $this->container->singleton(
            Router::class,
            fn (Container $container) => $container->get(RouteReader::class)
        );

        $this->container->singleton(
            RouteConfig::class,
            fn () => new RouteConfig(
                controllers: [
                    HomeController::class,
                    ProductController::class,
                ]
            ));
    }

    public function testRouter(): void
    {
        $router = $this->container->get(RouteReader::class);

        $nodes = $router->read();

        $this->assertIsArray($nodes);
    }

}
