<?php

namespace Alphonse\DependencyInjector;

interface InstantiatorInterface
{
    /**
     * @param string $ofClass - fully-qualified class name to create an instance from
     * @param bool $andFlushArguments - whether or not to delete arguments after instantation
     *  setting to false might be useful when creating multiple instances of the same class
     *  while keeping some arguments the same
     *
     * @return mixed - an instance of the specified class
     */
    public function createInstance(string $ofClass, bool $andFlushArguments = true): mixed;

    /**
     * @param string $named - the name of the argument to assign
     * @param mixed $being - the value to assign to the argument
     */
    public function assignConstructorArgument(string $named, mixed $being): void;

    /**
     * @return array<string,mixed> - map of so far assigned arguments name to arguments value
     */
    public function getAssignedConstructorArguments(): array;

    /**
     * @param string $name - the name of the argument to check for assignment
     *
     * @return bool - true if the argument has been assigned, false otherwise
     */
    public function hasAssignedConstructorArgument(string $named): bool;
}
