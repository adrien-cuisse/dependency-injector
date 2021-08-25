<?php

namespace Alphonse\DependencyInjector\Tests\Fixtures;

final class ConstructorWith1ArgumentFixture
{
    public function __construct(private ConstructorWithoutArgumentFixture $fixture)
    {
    }
}