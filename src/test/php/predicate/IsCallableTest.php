<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use bovigo\assert\AssertionFailure;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
use function bovigo\assert\predicate\isCallable;
use function bovigo\assert\predicate\isNotCallable;
/**
 * Test for bovigo\assert\assert\predicate\isCallable() and bovigo\assert\assert\predicate\isNotCallable().
 */
#[Group('predicate')]
class IsCallableTest extends TestCase
{
    public static function exampleStaticCallable(): void {}

    public static function provideValidCallables(): iterable
    {
        yield 'anonymous function' => [function() {}];
        yield 'arrow function'     => [fn() => 303];
        yield 'string callable'    => ['is_callable'];
        yield 'static callable'    => [[__CLASS__, 'exampleStaticCallable']];
        $foo = new class() {
            public function exampleCallable(): void { }
        };
        yield 'callable on object' => [[$foo, 'exampleCallable']];
    }

    public static function provideInvalidCallables(): iterable
    {
        yield 'array'  => [['foo']];
        yield 'float'  => [30.3];
        yield 'object' => [new stdClass()];
    }

    #[Test]
    #[DataProvider('provideValidCallables')]
    public function validCallablesAreRecognized(callable $value): void
    {
        assertThat($value, isCallable());
    }

    #[Test]
    #[DataProvider('provideInvalidCallables')]
    public function invalidCallablesAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isCallable()))
            ->throws(AssertionFailure::class);
    }

    #[Test]
    #[DataProvider('provideInvalidCallables')]
    public function invalidCallablesAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotCallable());
    }

    #[Test]
    #[DataProvider('provideValidCallables')]
    public function validCallablesAreRejectedOnNegation(callable $value): void
    {
        expect(fn() => assertThat($value, isNotCallable()))
            ->throws(AssertionFailure::class);
    }
}