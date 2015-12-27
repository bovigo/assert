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
 * Tests for bovigo\assert\predicate\IsNull.
 *
 * @group  predicate
 */
class IsNullTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function evaluatesToTrueIfGivenValueIsNull()
    {
        assert(isNull()->test(null), isTrue());
    }

    /**
     * @return  array
     */
    public function nonNullValues()
    {
        return [
          'boolean true'     => [true],
          'boolean false'    => [false],
          'non-empty string' => ['foo'],
          'empty string'     => [''],
          'empty array'      => [[]],
          'non-empty array'  => [[1]],
          'int 0'            => [0],
          'int non-0'        => [303]
        ];
    }

    /**
     * @param  mixed  $nonNullValue
     * @test
     * @dataProvider  nonNullValues
     */
    public function evaluatesToFalseIfGivenValueIsNotNull($nonNullValue)
    {
        assert(isNull()->test($nonNullValue), isFalse());
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that an array is null.
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        assert([], isNull());
    }
}
