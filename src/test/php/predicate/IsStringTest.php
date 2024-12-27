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
use function bovigo\assert\predicate\isString;
use function bovigo\assert\predicate\isNotAString;
/**
 * Test for bovigo\assert\assert\predicate\isString() and bovigo\assert\assert\predicate\isNotAString().
 */
#[Group('predicate')]
class IsStringTest extends TestCase
{
    public static function provideValidStrings(): iterable
    {
        yield 'empty string'  => [''];
        yield 'normal string' => ['example'];
    }

    public static function provideInvalidStrings(): iterable
    {
        yield 'array'  => [['foo']];
        yield 'float'  => [30.3];
        yield 'object' => [new stdClass()];
    }

    #[Test]
    #[DataProvider('provideValidStrings')]
    public function validStringsAreRecognized(string $value): void
    {
        assertThat($value, isString());
    }

    #[Test]
    #[DataProvider('provideInvalidStrings')]
    public function invalidStringsAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isString()))
            ->throws(AssertionFailure::class);
    }

    #[Test]
    #[DataProvider('provideInvalidStrings')]
    public function invalidStringsAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotAString());
    }

    #[Test]
    #[DataProvider('provideValidStrings')]
    public function validStringsAreRejectedOnNegation(string $value): void
    {
        expect(fn() => assertThat($value, isNotAString()))
            ->throws(AssertionFailure::class);
    }
}