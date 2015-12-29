<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\assert;
use function bovigo\assert\assertEmpty;
use function bovigo\assert\assertFalse;
use function bovigo\assert\assertNotEmpty;
use function bovigo\assert\assertTrue;
/**
 * Helper class for the test.
 */
class EmptyCountableExample implements \Countable
{
    private $count;
    public function __construct($count)
    {
        $this->count = $count;
    }
    public function count()
    {
        return $this->count;
    }
}
/**
 * Tests for bovigo\assert\predicate\IsEmpty.
 *
 * @group  predicate
 */
class IsEmptyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return  array
     */
    public function emptyValues()
    {
        return [
            'null'                  => [null],
            'boolean false'         => [false],
            'empty string'          => [''],
            'empty array'           => [[]],
            'integer 0'             => [0],
            'Countable with size 0' => [new EmptyCountableExample(0)]
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
    public function nonEmptyValues()
    {
        return [
            'boolean true'            => [true],
            'non-empty string'        => ['foo'],
            'non-empty array'         => [[1]],
            'Countable with size > 0' => [new EmptyCountableExample(1)]
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
        assert((string) new IsEmpty(), equals('is empty'));
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'foo' is empty.
     */
    public function assertionFailureWithStringContainsMeaningfulInformation()
    {
        assertEmpty('foo');
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 1 is empty.
     */
    public function assertionFailureWithIntegerContainsMeaningfulInformation()
    {
        assertEmpty(1);
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that true is empty.
     */
    public function assertionFailureWithBooleanContainsMeaningfulInformation()
    {
        assertEmpty(true);
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that an array is empty.
     */
    public function assertionFailureWithArrayContainsMeaningfulInformation()
    {
        assertEmpty(['foo']);
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that bovigo\assert\predicate\EmptyCountableExample implementing \Countable is empty.
     */
    public function assertionFailureWithCountableContainsMeaningfulInformation()
    {
        assertEmpty(new EmptyCountableExample(1));
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
