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
use function bovigo\assert\predicate\isIterable;
use function bovigo\assert\predicate\isNotIterable;

/**
 * Test for bovigo\assert\assert\predicate\isIterable() and bovigo\assert\assert\predicate\isNotIterable().
 *
 * @group  predicate
 */
class IsIterableTest extends TestCase
{
    public function validIterables(): array
    {
        $generator = function() { yield true; };
        return [
            'empty array'  => [[]],
            'filled array' => [[1, 2, 3]],
            'iterator'     => [new \ArrayIterator([])],
            'generator'    => [$generator()]
        ];
    }

    public function invalidIterables(): array
    {
        return [
            'string' => ['foo'],
            'float'  => [30.3],
            'object' => [new \stdClass()]
        ];
    }

    /**
     * @param  iterable  $value
     * @test
     * @dataProvider  validIterables
     */
    public function validIterablesAreRecognized(iterable $value): void
    {
        assertThat($value, isIterable());
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidIterables
     */
    public function invalidIterablesAreRejected($value): void
    {
        expect(function() use($value) { assertThat($value, isIterable()); })
            ->throws(AssertionFailure::class);
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  invalidIterables
     */
    public function invalidIterablesAreRecognizedOnNegation($value): void
    {
        assertThat($value, isNotIterable());
    }

    /**
     * @param  iterable  $value
     * @test
     * @dataProvider  validIterables
     */
    public function validIterablesAreRejectedOnNegation(iterable $value): void
    {
        expect(function() use($value) { assertThat($value, isNotIterable()); })
            ->throws(AssertionFailure::class);
    }
}