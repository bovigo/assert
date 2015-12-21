<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\assert;
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
        assert(isEmpty()->test($emptyValue), isTrue());
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
        assert(isEmpty()->test($nonEmptyValue), isFalse());
    }

    /**
     * @test
     */
    public function stringRepresentation()
    {
        assert((string) new IsEmpty(), equals('is empty'));
    }
}
