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
use function bovigo\assert\predicate\isNumeric;
use function bovigo\assert\predicate\isNotNumeric;
/**
 * Test for bovigo\assert\assert\predicate\isNumeric() and bovigo\assert\assert\predicate\isNotNumeric().
 *
 * @group  predicate
 */
class IsNumericTest extends TestCase
{
    public function validNumerics(): Generator
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

    public function invalidNumerics(): Generator
    {
        yield 'string' => ['foo'];
        yield 'array'  => [[30.3]];
        yield 'object' => [new stdClass()];
    }

    /**
     * @param  int|float  $value
     * @test
     * @dataProvider  validNumerics
     */
    public function validNumericsAreRecognized($value): void
    {
        assertThat($value, isNumeric());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidNumerics
     */
    public function invalidNumericsAreRejected($value): void
    {
        expect(fn() => assertThat($value, isNumeric()))
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  int|float  $value
     * @test
     * @dataProvider  invalidNumerics
     */
    public function invalidArraysAreRecognizedOnNegation($value): void
    {
        assertThat($value, isNotNumeric());
    }

    /**
     * @param  int|float  $value
     * @test
     * @dataProvider  validNumerics
     */
    public function validNumericsAreRejectedOnNegation($value): void
    {
        expect(fn() => assertThat($value, isNotNumeric()))
            ->throws(AssertionFailure::class);
    }
}