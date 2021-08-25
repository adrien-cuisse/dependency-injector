<?php

namespace Alphonse\DependencyInjector;

final class Instantiator implements InstantiatorInterface
{
    private array $constructorArguments = [];

    public function createInstance(string $ofClass, bool $andFlushArguments = true): mixed
    {
        $createdInstance = new $ofClass(...$this->constructorArguments);

        if ($andFlushArguments) {
            $this->constructorArguments = [];
        }

        return $createdInstance;
    }

    public function assignConstructorArgument(string $named, mixed $being): void
    {
        $this->constructorArguments[$named] = $being;
    }

    public function getAssignedConstructorArguments(): array
    {
        return $this->constructorArguments;
    }

    public function hasAssignedConstructorArgument(string $named): bool
    {
        return isset($this->constructorArguments[$named]);
    }
}
