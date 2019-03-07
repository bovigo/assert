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
use function bovigo\assert\predicate\isFloat;
use function bovigo\assert\predicate\isNotFloat;

/**
 * Test for bovigo\assert\assert\predicate\isFloat() and bovigo\assert\assert\predicate\isNotFloat().
 *
 * @group  predicate
 */
class IsFloatTest extends TestCase
{
    public function validFloats(): array
    {
        return [
            'zero float'     => [0.0],
            'positive float' => [30.3],
            'negative float' => [-31.3]
        ];
    }

    public function invalidFloats(): array
    {
        return [
            'string' => ['foo'],
            'int'    => [303],
            'object' => [new \stdClass()]
        ];
    }

    /**
     * @param  float  $value
     * @test
     * @dataProvider  validFloats
     */
    public function validFloatsAreRecognized(float $value)
    {
        assertThat($value, isFloat());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidFloats
     */
    public function invalidFloatsAreRejected($value)
    {
        expect(function() use($value) { assertThat($value, isFloat()); })
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidFloats
     */
    public function invalidAFloatsAreRecognizedOnNegation($value)
    {
        assertThat($value, isNotFloat());
    }

    /**
     * @param  float  $value
     * @test
     * @dataProvider  validFloats
     */
    public function validFloatsAreRejectedOnNegation(float $value)
    {
        expect(function() use($value) { assertThat($value, isNotFloat()); })
            ->throws(AssertionFailure::class);
    }
}