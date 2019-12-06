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
use PHPUnit\Framework\TestCase;

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
    public function validNumerics(): array
    {
        return [
            'default int'             => [0],
            'normal int'              => [303],
            'negative int'            => [-313],
            'default float'           => [0.0],
            'normal float'            => [30.3],
            'negative float'          => [-3.13],
            'numeric string'          => ['42'],
            'negative numeric string' => ['-42']
        ];
    }

    public function invalidNumerics(): array
    {
        return [
            'string' => ['foo'],
            'array'  => [[30.3]],
            'object' => [new \stdClass()]
        ];
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
        expect(function() use($value) { assertThat($value, isNumeric()); })
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
        expect(function() use($value) { assertThat($value, isNotNumeric()); })
            ->throws(AssertionFailure::class);
    }
}