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

use function bovigo\assert\{
    assertThat,
    assertEmpty,
    assertFalse,
    assertNotEmpty,
    assertTrue,
    expect
};
/**
 * Tests for bovigo\assert\predicate\IsEmpty.
 */
#[Group('predicate')]
class IsEmptyTest extends TestCase
{
    public static function provideEmptyValues(): iterable
    {
        yield 'null'                  => [null];
        yield 'boolean false'         => [false];
        yield 'empty string'          => [''];
        yield 'empty array'           => [[]];
        yield 'integer 0'             => [0];
        yield 'Countable with size 0' => [new IsEmptyCountableExample(0)];
    }

    #[Test]
    #[DataProvider('provideEmptyValues')]
    public function evaluatesToTrueIfGivenValueIsEmpty(mixed $emptyValue): void
    {
        assertTrue(isEmpty()->test($emptyValue));
    }

    public static function provideNonEmptyValues(): iterable
    {
        yield 'boolean true'            => [true];
        yield 'non-empty string'        => ['foo'];
        yield 'non-empty array'         => [[1]];
        yield 'Countable with size > 0' => [new IsEmptyCountableExample(1)];
    }

    #[Test]
    #[DataProvider('provideNonEmptyValues')]
    public function evaluatesToFalseIfGivenValueIsNotEmpty(mixed $nonEmptyValue): void
    {
        assertFalse(isEmpty()->test($nonEmptyValue));
    }

    #[Test]
    public function stringRepresentation(): void
    {
        assertThat((string) new IsEmpty(), equals('is empty'));
    }

    #[Test]
    public function assertionFailureWithStringContainsMeaningfulInformation(): void
    {
        expect(fn() => assertEmpty('foo'))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that 'foo' is empty.");
    }

    #[Test]
    public function assertionFailureWithIntegerContainsMeaningfulInformation(): void
    {
        expect(fn() => assertEmpty(1))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that 1 is empty.");
    }

    #[Test]
    public function assertionFailureWithBooleanContainsMeaningfulInformation(): void
    {
       expect(fn() => assertEmpty(true))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that true is empty.");
    }

    #[Test]
    public function assertionFailureWithArrayContainsMeaningfulInformation(): void
    {
         expect(fn() => assertEmpty(['foo']))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that an array is empty.");
    }

    #[Test]
    public function assertionFailureWithCountableContainsMeaningfulInformation(): void
    {
        expect(fn() => assertEmpty(new IsEmptyCountableExample(1)))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that " . IsEmptyCountableExample::class
                . " implementing \Countable is empty."
            );
    }

    /**
     * @since 1.3.0
     */
    #[Test]
    public function aliasAssertNotEmpty(): void
    {
        assertTrue(assertNotEmpty(303));
    }
}
