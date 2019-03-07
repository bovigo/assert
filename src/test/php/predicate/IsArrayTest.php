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
use function bovigo\assert\predicate\isArray;
use function bovigo\assert\predicate\isNotAnArray;

/**
 * Test for bovigo\assert\assert\predicate\isArray() and bovigo\assert\assert\predicate\isNotAnArray().
 *
 * @group  predicate
 */
class IsArrayTest extends TestCase
{
    public function validArrays(): array
    {
        return [
            'empty array'       => [[]],
            'normal array'      => [[1, 2, 3]],
            'associative array' => [['one' => 1, 'two' => 2, 'three' => 3]]
        ];
    }

    public function invalidArrays(): array
    {
        return [
            'string' => ['foo'],
            'number' => [303],
            'object' => [new \stdClass()]
        ];
    }

    /**
     * @param  array  $value
     * @test
     * @dataProvider  validArrays
     */
    public function validArraysAreRecognized(array $value)
    {
        assertThat($value, isArray());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidArrays
     */
    public function invalidArraysAreRejected($value)
    {
        expect(function() use($value) { assertThat($value, isArray()); })
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidArrays
     */
    public function invalidArraysAreRecognizedOnNegation($value)
    {
        assertThat($value, isNotAnArray());
    }

    /**
     * @param  array  $value
     * @test
     * @dataProvider  validArrays
     */
    public function validArraysAreRejectedOnNegation(array $value)
    {
        expect(function() use($value) { assertThat($value, isNotAnArray()); })
            ->throws(AssertionFailure::class);
    }
}