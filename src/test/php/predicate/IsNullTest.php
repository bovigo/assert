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

use function bovigo\assert\{
    assertFalse,
    assertNotNull,
    assertNull,
    assertTrue,
    expect
};
/**
 * Tests for bovigo\assert\predicate\IsNull.
 *
 * @group predicate
 */
class IsNullTest extends TestCase
{
    #[Test]
    public function evaluatesToTrueIfGivenValueIsNull(): void
    {
        assertTrue(isNull()->test(null));
    }

    public static function nonNullValues(): Generator
    {
        yield 'boolean true'     => [true];
        yield 'boolean false'    => [false];
        yield 'non-empty string' => ['foo'];
        yield 'empty string'     => [''];
        yield 'empty array'      => [[]];
        yield 'non-empty array'  => [[1]];
        yield 'int 0'            => [0];
        yield 'int non-0'        => [303];
    }

    #[Test]
    #[DataProvider('nonNullValues')]
    public function evaluatesToFalseIfGivenValueIsNotNull(mixed $nonNullValue): void
    {
        assertFalse(isNull()->test($nonNullValue));
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertNull([]))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that an array is null.");
    }

    /**
     * @since  1.3.0
     */
    #[Test]
    public function aliasAssertNotNull(): void
    {
        assertTrue(assertNotNull(303));
    }
}
