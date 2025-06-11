<?php

namespace Qup\Swagger;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeTraverser;
use PhpParser\PhpVersion;
use Qup\Http\Route;
use Qup\Http\RouteConfig;
use PhpParser\ParserFactory;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

readonly class RouteReader
{
    public function __construct(private RouteConfig $config)
    {
    }

    /**
     * @throws ReflectionException
     */
    public function read(): array
    {
        $result = [];

        foreach ($this->config->controllers as $controllerClass) {
            $controllerRoutes = $this->extractRoutesFromController($controllerClass);
            $result[$controllerClass] = $controllerRoutes;
        }

        return $result;
    }

    /**
     * @throws ReflectionException
     */
    private function extractRoutesFromController(string $controllerClass): array
    {
        $reflector = new ReflectionClass($controllerClass);
        $filePath = $reflector->getFileName();
        $code = file_get_contents($filePath);

        $parser = (new ParserFactory)->createForVersion(PhpVersion::getHostVersion());
        $ast = $parser->parse($code);
        $namespace = $reflector->getNamespaceName();

        $routes = [];

        foreach ($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $routeData = $this->getRouteAttributeData($method);
            if (!$routeData) {
                continue;
            }

            [$httpMethod, $uri] = $routeData;

            $classMethodNode = $this->findClassMethodNode($ast, $method->getName());
            if (!$classMethodNode) {
                continue;
            }

            $visitor = new ControllerMethodReturnVisitor($namespace);
            $traverser = new NodeTraverser();
            $traverser->addVisitor($visitor);
            $traverser->traverse([$classMethodNode]);

            $returns = $visitor->getMethodReturnMap()[$method->getName()] ?? [];

            $routes[] = [
                'method' => $httpMethod,
                'uri' => $uri,
                'params' => $this->getMethodParameters($method),
                'returns' => $returns,
            ];
        }

        return $routes;
    }

    private function getRouteAttributeData(ReflectionMethod $method): ?array
    {
        $attributes = $method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);
        if (empty($attributes)) {
            return null;
        }

        $route = $attributes[0]->newInstance();
        return [$route->method->name, $route->uri];
    }

    private function findClassMethodNode(array $ast, string $methodName): ?ClassMethod
    {
        foreach ($ast as $stmt) {
            if ($stmt instanceof Namespace_) {
                foreach ($stmt->stmts as $nsStmt) {
                    if ($nsStmt instanceof Class_) {
                        foreach ($nsStmt->stmts as $classStmt) {
                            if ($classStmt instanceof ClassMethod &&
                                $classStmt->name->toString() === $methodName) {
                                return $classStmt;
                            }
                        }
                    }
                }
            }

            if ($stmt instanceof Class_) {
                foreach ($stmt->stmts as $classStmt) {
                    if ($classStmt instanceof ClassMethod &&
                        $classStmt->name->toString() === $methodName) {
                        return $classStmt;
                    }
                }
            }
        }

        return null;
    }

    private function getMethodParameters(ReflectionMethod $method): array
    {
        $params = [];

        foreach ($method->getParameters() as $param) {
            $type = $param->getType();
            $typeName = $type instanceof ReflectionNamedType ? $type->getName() : 'mixed';

            $params[] = [
                'name' => $param->getName(),
                'type' => $typeName,
                'nullable' => $type instanceof ReflectionNamedType && $type->allowsNull(),
                'hasDefault' => $param->isDefaultValueAvailable(),
                'default' => $param->isDefaultValueAvailable() ? $param->getDefaultValue() : null,
            ];
        }

        return $params;
    }
}