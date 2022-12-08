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
use Generator;
use PHPUnit\Framework\TestCase;
use stdClass;

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
use function bovigo\assert\predicate\isString;
use function bovigo\assert\predicate\isNotAString;
/**
 * Test for bovigo\assert\assert\predicate\isString() and bovigo\assert\assert\predicate\isNotAString().
 *
 * @group  predicate
 */
class IsStringTest extends TestCase
{
    public function validStrings(): Generator
    {
        yield 'empty string'  => [''];
        yield 'normal string' => ['example'];
    }

    public function invalidStrings(): Generator
    {
        yield 'array'  => [['foo']];
        yield 'float'  => [30.3];
        yield 'object' => [new stdClass()];
    }

    /**
     * @test
     * @dataProvider  validStrings
     */
    public function validStringsAreRecognized(string $value): void
    {
        assertThat($value, isString());
    }

    /**
     * @test
     * @dataProvider  invalidStrings
     */
    public function invalidStringsAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isString()))
            ->throws(AssertionFailure::class);
    }

    /**
     * @test
     * @dataProvider  invalidStrings
     */
    public function invalidStringsAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotAString());
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  validStrings
     */
    public function validStringsAreRejectedOnNegation(string $value): void
    {
        expect(fn() => assertThat($value, isNotAString()))
            ->throws(AssertionFailure::class);
    }
}