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
use function bovigo\assert\predicate\isFloat;
use function bovigo\assert\predicate\isNotFloat;
/**
 * Test for bovigo\assert\assert\predicate\isFloat() and bovigo\assert\assert\predicate\isNotFloat().
 */
#[Group('predicate')]
class IsFloatTest extends TestCase
{
    public static function provideValidFloats(): iterable
    {
        yield 'zero float'     => [0.0];
        yield 'positive float' => [30.3];
        yield 'negative float' => [-31.3];
    }

    public static function provideInvalidFloats(): iterable
    {
        yield 'string' => ['foo'];
        yield 'int'    => [303];
        yield 'object' => [new stdClass()];
    }

    #[Test]
    #[DataProvider('provideValidFloats')]
    public function validFloatsAreRecognized(float $value): void
    {
        assertThat($value, isFloat());
    }

    #[Test]
    #[DataProvider('provideInvalidFloats')]
    public function invalidFloatsAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isFloat()))
            ->throws(AssertionFailure::class);
    }

    #[Test]
    #[DataProvider('provideInvalidFloats')]
    public function invalidAFloatsAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotFloat());
    }

    #[Test]
    #[DataProvider('provideValidFloats')]
    public function validFloatsAreRejectedOnNegation(float $value): void
    {
        expect(fn() => assertThat($value, isNotFloat()))
            ->throws(AssertionFailure::class);
    }
}