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
 *
 * @group  predicate
 */
class IsEmptyTest extends TestCase
{
    /**
     * @return  array
     */
    public function emptyValues(): array
    {
        return [
            'null'                  => [null],
            'boolean false'         => [false],
            'empty string'          => [''],
            'empty array'           => [[]],
            'integer 0'             => [0],
            'Countable with size 0' => [new IsEmptyCountableExample(0)]
        ];
    }

    /**
     * @test
     * @dataProvider  emptyValues
     */
    public function evaluatesToTrueIfGivenValueIsEmpty($emptyValue)
    {
        assertTrue(isEmpty()->test($emptyValue));
    }

    /**
     * @return  array
     */
    public function nonEmptyValues(): array
    {
        return [
            'boolean true'            => [true],
            'non-empty string'        => ['foo'],
            'non-empty array'         => [[1]],
            'Countable with size > 0' => [new IsEmptyCountableExample(1)]
        ];
    }

    /**
     * @param  mixed  $nonEmptyValue
     * @test
     * @dataProvider  nonEmptyValues
     */
    public function evaluatesToFalseIfGivenValueIsNotEmpty($nonEmptyValue)
    {
        assertFalse(isEmpty()->test($nonEmptyValue));
    }

    /**
     * @test
     */
    public function stringRepresentation()
    {
        assertThat((string) new IsEmpty(), equals('is empty'));
    }

    /**
     * @test
     */
    public function assertionFailureWithStringContainsMeaningfulInformation()
    {
        expect(function() { assertEmpty('foo'); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 'foo' is empty.");
    }

    /**
     * @test
     */
    public function assertionFailureWithIntegerContainsMeaningfulInformation()
    {
        expect(function() { assertEmpty(1); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 1 is empty.");
    }

    /**
     * @test
     */
    public function assertionFailureWithBooleanContainsMeaningfulInformation()
    {
       expect(function() { assertEmpty(true); })
                ->throws(AssertionFailure::class)
               ->withMessage("Failed asserting that true is empty.");
    }

    /**
     * @test
     */
    public function assertionFailureWithArrayContainsMeaningfulInformation()
    {
         expect(function() { assertEmpty(['foo']); })
                ->throws(AssertionFailure::class)
                 ->withMessage("Failed asserting that an array is empty.");
    }

    /**
     * @test
     */
    public function assertionFailureWithCountableContainsMeaningfulInformation()
    {
        expect(function() { assertEmpty(new IsEmptyCountableExample(1)); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that " . IsEmptyCountableExample::class
                        . " implementing \Countable is empty."
        );
    }

    /**
     * @test
     * @since  1.3.0
     */
    public function aliasAssertNotEmpty()
    {
        assertTrue(assertNotEmpty(303));
    }
}
