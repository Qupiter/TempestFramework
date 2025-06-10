<?php

namespace Qup\Http;

use Qup\Interfaces\Container;
use Qup\Interfaces\Request;
use Qup\Interfaces\Response;
use Qup\Interfaces\Router;
use ReflectionAttribute;
use ReflectionException;

final readonly class GenericRouter implements Router
{
    public function __construct(
        private Container   $container,
        private RouteConfig $config
    )
    {
    }

    /**
     * @throws ReflectionException
     */
    public function dispatch(Request $request): Response
    {
        foreach ($this->config->controllers as $controllerClass) {
            $reflectionController = new \ReflectionClass($controllerClass);

            foreach ($reflectionController->getMethods() as $method) {
                $routeAttribute =
                    $method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF)[0] ?? null;

                // no attributes
                if (!$routeAttribute) continue;

                /** @var Route $route */
                $route = $routeAttribute->newInstance();

                // method doesnt match
                if ($route->method !== $request->getMethod()) continue;

                $params = $this->resolveParams($route->uri, $request->getUri());

                if($params === null) continue;

                $controller = $this->container->get($controllerClass);

                return $controller->{$method->getName()}(...$params);
            }
        }

        return new GenericResponse(Status::HTTP_404, 'Not Found');
    }

    private function resolveParams(string $routeUri, string $requestUri): ?array
    {
        $result = preg_match_all('/\{\w+}/', $routeUri, $tokens);

        if (!$result) return null;

        $tokens = $tokens[0];

        $matchingRegex = '/^' . str_replace(
            ['/', ...$tokens],
            ['\\/', ...array_fill(0, count($tokens), '([\w\d\s)]+)')],
            $routeUri
        ) . '$/';

        $result = preg_match_all($matchingRegex, $requestUri, $matches);

        if($result === 0) return [];

        unset($matches[0]);

        $matches = array_values($matches);

        $valueMap = [];

        foreach ($matches as $i => $match) {
            $valueMap[trim($tokens[$i], '{}')] = $matches[0][0];
        }

        return $valueMap;
    }
}