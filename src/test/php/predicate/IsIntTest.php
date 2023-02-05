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
use function bovigo\assert\predicate\isInt;
use function bovigo\assert\predicate\isNotInt;
/**
 * Test for bovigo\assert\assert\predicate\isInt() and bovigo\assert\assert\predicate\isNotInt().
 *
 * @group predicate
 */
class IsIntTest extends TestCase
{
    public static function validInts(): Generator
    {
        yield 'default int'  => [0];
        yield 'normal int'   => [303];
        yield 'negative int' => [-313];
    }

    public static function invalidInts(): Generator
    {
        yield 'string' => ['foo'];
        yield 'float'  => [30.3];
        yield 'object' => [new stdClass()];
    }

    #[Test]
    #[DataProvider('validInts')]
    public function validIntsAreRecognized(int $value): void
    {
        assertThat($value, isInt());
    }

    #[Test]
    #[DataProvider('invalidInts')]
    public function invalidIntsAreRejected(mixed $value): void
    {
        expect(fn() => assertThat($value, isInt()))
            ->throws(AssertionFailure::class);
    }

    #[Test]
    #[DataProvider('invalidInts')]
    public function invalidIntsAreRecognizedOnNegation(mixed $value): void
    {
        assertThat($value, isNotInt());
    }

    #[Test]
    #[DataProvider('validInts')]
    public function validIntsAreRejectedOnNegation(int $value): void
    {
        expect(fn() => assertThat($value, isNotInt()))
            ->throws(AssertionFailure::class);
    }
}