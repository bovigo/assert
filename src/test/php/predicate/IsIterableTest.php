<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use ArrayIterator;
use bovigo\assert\AssertionFailure;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

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
    public static function validIterables(): Generator
    {
        yield 'empty array'  => [[]];
        yield 'filled array' => [[1, 2, 3]];
        yield 'iterator'     => [new ArrayIterator([])];
        $generator = fn() => yield true;
        yield 'generator'    => [$generator()];
    }

    public static function invalidIterables(): Generator
    {
        yield 'string' => ['foo'];
        yield 'float'  => [30.3];
        yield 'object' => [new stdClass()];
    }

    #[Test]
    #[DataProvider('validIterables')]
    public function validIterablesAreRecognized(iterable $value): void
    {
        assertThat($value, isIterable());
    }

    #[Test]
    #[DataProvider('invalidIterables')]
    public function invalidIterablesAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isIterable()))
            ->throws(AssertionFailure::class);
    }

    #[Test]
    #[DataProvider('invalidIterables')]
    public function invalidIterablesAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotIterable());
    }

    /**
     * @param  iterable<mixed>  $value
     */
    #[Test]
    #[DataProvider('validIterables')]
    public function validIterablesAreRejectedOnNegation(iterable $value): void
    {
        expect(fn() => assertThat($value, isNotIterable()))
            ->throws(AssertionFailure::class);
    }
}