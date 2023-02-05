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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
use function bovigo\assert\predicate\isScalar;
use function bovigo\assert\predicate\isNotScalar;
/**
 * Test for bovigo\assert\assert\predicate\isScalar() and bovigo\assert\assert\predicate\isNotScalar().
 *
 * @group predicate
 */
class IsScalarTest extends TestCase
{
    public static function validScalars(): Generator
    {
        yield 'empty string'  => [''];
        yield 'normal string' => ['example'];
        yield 'int'           => [303];
        yield 'float'         => [3.13];
    }

    public static function invalidScalars(): Generator
    {
        yield 'array'  => [['foo']];
        yield 'object' => [new stdClass()];
    }

    #[Test]
    #[DataProvider('validScalars')]
    public function validScalarsAreRecognized(mixed $value): void
    {
        assertThat($value, isScalar());
    }

    #[Test]
    #[DataProvider('invalidScalars')]
    public function invalidScalarsAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isScalar()))
            ->throws(AssertionFailure::class);
    }

    #[Test]
    #[DataProvider('invalidScalars')]
    public function invalidScalarsAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotScalar());
    }

    #[Test]
    #[DataProvider('validScalars')]
    public function validScalarsAreRejectedOnNegation(mixed $value): void
    {
        expect(fn() => assertThat($value, isNotScalar()))
            ->throws(AssertionFailure::class);
    }
}