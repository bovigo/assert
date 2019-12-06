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
use function bovigo\assert\predicate\isInt;
use function bovigo\assert\predicate\isNotInt;

/**
 * Test for bovigo\assert\assert\predicate\isInt() and bovigo\assert\assert\predicate\isNotInt().
 *
 * @group  predicate
 */
class IsIntTest extends TestCase
{
    /**
     * @return  array<string,array<int>>
     */
    public function validInts(): array
    {
        return [
            'default int'  => [0],
            'normal int'   => [303],
            'negative int' => [-313]
        ];
    }

    /**
     * @return  array<string,array<mixed>>
     */
    public function invalidInts(): array
    {
        return [
            'string' => ['foo'],
            'float'  => [30.3],
            'object' => [new \stdClass()]
        ];
    }

    /**
     * @param  int  $value
     * @test
     * @dataProvider  validInts
     */
    public function validIntsAreRecognized(int $value): void
    {
        assertThat($value, isInt());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidInts
     */
    public function invalidIntsAreRejected($value): void
    {
        expect(function() use($value) { assertThat($value, isInt()); })
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidInts
     */
    public function invalidIntsAreRecognizedOnNegation($value): void
    {
        assertThat($value, isNotInt());
    }

    /**
     * @param  int  $value
     * @test
     * @dataProvider  validInts
     */
    public function validIntsAreRejectedOnNegation(int $value): void
    {
        expect(function() use($value) { assertThat($value, isNotInt()); })
            ->throws(AssertionFailure::class);
    }
}