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
use function bovigo\assert\predicate\isArray;
use function bovigo\assert\predicate\isNotAnArray;
/**
 * Test for bovigo\assert\assert\predicate\isArray() and bovigo\assert\assert\predicate\isNotAnArray().
 */
#[Group('predicate')]
class IsArrayTest extends TestCase
{
    public static function provideValidArrays(): iterable
    {
        yield 'empty array'       => [[]];
        yield'normal array'       => [[1, 2, 3]];
        yield 'associative array' => [['one' => 1, 'two' => 2, 'three' => 3]];
    }

    public static function provideInvalidArrays(): iterable
    {
        yield 'string' => ['foo'];
        yield 'number' => [303];
        yield 'object' => [new stdClass()];
    }

    /**
     * @param array<mixed> $value
     */
    #[Test]
    #[DataProvider('provideValidArrays')]
    public function validArraysAreRecognized(array $value): void
    {
        assertThat($value, isArray());
    }

    #[Test]
    #[DataProvider('provideInvalidArrays')]
    public function invalidArraysAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isArray()))
            ->throws(AssertionFailure::class);
    }

    #[Test]
    #[DataProvider('provideInvalidArrays')]
    public function invalidArraysAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotAnArray());
    }

    /**
     * @param array<mixed> $value
     */
    #[Test]
    #[DataProvider('provideValidArrays')]
    public function validArraysAreRejectedOnNegation(array $value): void
    {
        expect(fn() => assertThat($value, isNotAnArray()))
            ->throws(AssertionFailure::class);
    }
}