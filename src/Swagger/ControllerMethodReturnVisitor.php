<?php

namespace Qup\Swagger;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class ControllerMethodReturnVisitor extends NodeVisitorAbstract
{
    private array $methods = [];
    private array $uses = [];
    private string $namespace = '';
    private string $currentMethod = '';

    public function __construct(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Stmt\Use_) {
            foreach ($node->uses as $use) {
                $alias = $use->alias ? $use->alias->toString() : $use->name->getLast();
                $this->uses[$alias] = $use->name->toString();
            }
        }

        if ($node instanceof Node\Stmt\ClassMethod) {
            $this->currentMethod = $node->name->toString();
            if (!isset($this->methods[$this->currentMethod])) {
                $this->methods[$this->currentMethod] = [];
            }
        }

        if ($node instanceof Node\Stmt\Return_ && $node->expr instanceof Node\Expr\New_) {
            $classNode = $node->expr->class;

            if ($classNode instanceof Node\Name) {
                $className = $classNode->toString();

                if ($classNode->isFullyQualified()) {
                    $fqcn = ltrim($className, '\\');
                } elseif (isset($this->uses[$className])) {
                    $fqcn = $this->uses[$className];
                } else {
                    $fqcn = $this->namespace . '\\' . $className;
                }

                if (!in_array($fqcn, $this->methods[$this->currentMethod])) {
                    $this->methods[$this->currentMethod][] = $fqcn;
                }
            }
        }
    }

    public function getMethodReturnMap(): array
    {
        return $this->methods;
    }
}
