<?php

namespace Tests\Http;

use Qup\Http\GenericRequest;
use Qup\Http\GenericRouter;
use Qup\Http\Method;
use Qup\Http\Status;
use Tests\TestCase;

class GenericRouterTest extends TestCase
{
    public function testRouter(): void
    {
        $router = $this->container->get(GenericRouter::class);

        $response = $router->dispatch(
            new GenericRequest(
                method: Method::GET,
                uri: '/home',
                body: []
            )
        );

        $this->assertSame(Status::HTTP_200, $response->getStatus());
        $this->assertSame('OK', $response->getBody());
    }

    public function testRouterWithVariables(): void
    {
        $router = $this->container->get(GenericRouter::class);

        $response = $router->dispatch(
            new GenericRequest(
                method: Method::GET,
                uri: '/show/Qupiter',
                body: []
            )
        );

        $this->assertSame(Status::HTTP_200, $response->getStatus());
        $this->assertSame('Qupiter', $response->getBody());
    }
}
