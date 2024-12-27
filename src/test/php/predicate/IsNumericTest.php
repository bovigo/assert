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
use function bovigo\assert\predicate\isNumeric;
use function bovigo\assert\predicate\isNotNumeric;
/**
 * Test for bovigo\assert\assert\predicate\isNumeric() and bovigo\assert\assert\predicate\isNotNumeric().
 */
#[Group('predicate')]
class IsNumericTest extends TestCase
{
    public static function provideValidNumerics(): iterable
    {
        yield 'default int'             => [0];
        yield 'normal int'              => [303];
        yield 'negative int'            => [-313];
        yield 'default float'           => [0.0];
        yield 'normal float'            => [30.3];
        yield 'negative float'          => [-3.13];
        yield 'numeric string'          => ['42'];
        yield 'negative numeric string' => ['-42'];
    }

    public static function provideInvalidNumerics(): iterable
    {
        yield 'string' => ['foo'];
        yield 'array'  => [[30.3]];
        yield 'object' => [new stdClass()];
    }

    #[Test]
    #[DataProvider('provideValidNumerics')]
    public function validNumericsAreRecognized(int|float|string $value): void
    {
        assertThat($value, isNumeric());
    }

    #[Test]
    #[DataProvider('provideInvalidNumerics')]
    public function invalidNumericsAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isNumeric()))
            ->throws(AssertionFailure::class);
    }

    #[Test]
    #[DataProvider('provideInvalidNumerics')]
    public function invalidArraysAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotNumeric());
    }

    #[Test]
    #[DataProvider('provideValidNumerics')]
    public function validNumericsAreRejectedOnNegation(int|float|string $value): void
    {
        expect(fn() => assertThat($value, isNotNumeric()))
            ->throws(AssertionFailure::class);
    }
}