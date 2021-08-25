<?php

namespace Alphonse\DependencyInjector\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use Alphonse\DependencyInjector\InstantiatorInterface;
use Alphonse\DependencyInjector\Tests\Traits\CreatesInstantiator;
use Alphonse\DependencyInjector\Tests\Fixtures\ConstructorWith1ArgumentFixture;
use Alphonse\DependencyInjector\Tests\Fixtures\ConstructorWithoutArgumentFixture;

/**
 * @coversDefaultClass Alphonse\DependencyInjector\Instantiator
 */
final class InstantiatorTest extends TestCase
{
    use CreatesInstantiator;

    private InstantiatorInterface $instantiator;

    public function setUp(): void
    {
        $this->instantiator = $this->createRealInstantiator();
    }

    /**
     * @test
     * @covers ::assignConstructorArgument
     */
    public function assigns_constructor_named_argument(): void
    {
        // given a constructor argument
        $argumentName = 'name';
        $argumentValue = '';

        // when adding it to the constructor of the target class
        $this->instantiator->assignConstructorArgument(
            named: $argumentName,
            being: $argumentValue,
        );

        // then the argument should be assigned
        $assignedConstructorArguments = $this->instantiator->getAssignedConstructorArguments();
        $this->assertArrayHasKey(
            key: $argumentName,
            array: $assignedConstructorArguments,
            message: "Instantiator didn't assign the argument '{$argumentName}'",
        );
    }

    /**
     * @test
     * @covers ::assignConstructorArgument
     */
    public function assigns_proper_value_to_constructor_named_argument(): void
    {
        // given a constructor argument
        $argumentName = 'name';
        $argumentValue = 'value';

        // when adding it to the constructor of the target class
        $this->instantiator->assignConstructorArgument(
            named: $argumentName,
            being: $argumentValue,
        );

        // then the argument should be assigned with the correct value
        $assignedConstructorArguments = $this->instantiator->getAssignedConstructorArguments();
        $assignedArgumentValue = $assignedConstructorArguments[$argumentName] ?? null;
        $this->assertSame(
            expected: $argumentValue,
            actual: $assignedArgumentValue,
            message: "Instantiator didn't assign proper value to the argument '{$argumentName}', expected '{$argumentValue}', got '{$assignedArgumentValue}'",
        );
    }

    /**
     * @test
     * @covers ::getAssignedConstructorArguments
     */
    public function returns_assigned_arguments(): void
    {
        // given a constructor argument
        $argumentName = 'name';
        $argumentValue = 'value';

        // when adding it to the constructor of the target class
        $this->instantiator->assignConstructorArgument(
            named: $argumentName,
            being: $argumentValue,
        );

        // then the assigned arguments list should contain the assigned argument
        $assignedConstructorArguments = $this->instantiator->getAssignedConstructorArguments();
        $this->assertSame(
            expected: [$argumentName => $argumentValue],
            actual: $assignedConstructorArguments,
            message: "Argument ['{$argumentName}' => '{$argumentValue}'] doesn't appear in constructor arguments list",
        );
    }

    /**
     * @test
     * @covers ::hasAssignedConstructorArgument
     */
    public function detects_assigned_arguments(): void
    {
        // given a constructor argument
        $argumentName = 'name';
        $argumentValue = '';

        // when adding it to the constructor of the target class
        $this->instantiator->assignConstructorArgument(
            named: $argumentName,
            being: $argumentValue,
        );

        // then the argument should be assigned
        $argumentWasAssigned = $this->instantiator->hasAssignedConstructorArgument(named: $argumentName);
        $this->assertTrue(
            condition: $argumentWasAssigned,
            message: "Instantiator failed to detect that the named argument '{$argumentName}' was assigned",
        );
    }

    /**
     * @test
     * @covers ::createInstance
     */
    public function instantiates_target_class(): void
    {
        // given  a target class
        $targetClass = ConstructorWithoutArgumentFixture::class;

        // when creating an instance of it
        $instance = $this->instantiator->createInstance(ofClass: $targetClass);

        // then the created instance should have the appropriate class
        $instantiatedClass = $instance::class;
        $this->assertInstanceOf(
            expected: $targetClass,
            actual: $instance,
            message: "Expected the instantiator to instantiante '{$targetClass}', got '{$instantiatedClass}'",
        );
    }

    public function flushProvider(): Generator
    {
        yield "Flush arguments" => [true];
        yield "Don't flush arguments" => [false];
    }

    /**
     * @test
     * @covers ::createInstance
     * @dataProvider flushProvider
     */
    public function flushes_arguments_after_instantiation(bool $flushExpectation): void
    {
        // given a target class
        $targetClass = ConstructorWith1ArgumentFixture::class;

        // when checking assigned arguments after creating an instance of it
        $this->instantiator->assignConstructorArgument(named: 'fixture', being: new ConstructorWithoutArgumentFixture);
        $this->instantiator->createInstance(ofClass: $targetClass, andFlushArguments: $flushExpectation);
        $assignedArgumentsAfterInstantiation = $this->instantiator->getAssignedConstructorArguments();

        // then the arguments list should be empty
        $argumentsGotFlushedAfterInstantiation = ($assignedArgumentsAfterInstantiation === []);
        $this->assertSame(
            expected: $flushExpectation,
            actual: $argumentsGotFlushedAfterInstantiation,
            message: "Expected constructor arguments to be " . $flushExpectation ? '' : 'not' . "flushed after instatiation",
        );
    }
}
