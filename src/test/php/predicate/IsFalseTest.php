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
 * Tests for bovigo\assert\predicate\IsFalse.
 *
 * @group  predicate
 */
class IsFalseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function evaluatesToTrueIfGivenValueIsFalse()
    {
        assert(isFalse()->test(false), isSameAs(true));
    }

    /**
     * @return  array
     */
    public function trueValues()
    {
        return [
          'boolean true'     => [true],
          'non-empty string' => ['foo'],
          'empty string'     => [''],
          'empty array'      => [[]],
          'non-empty array'  => [[1]]
        ];
    }

    /**
     * @param  mixed  $true
     * @test
     * @dataProvider  trueValues
     */
    public function evaluatesToFalseIfGivenValueIsFalse($true)
    {
        assert(isFalse()->test($true), isSameAs(false));
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 1 is false.
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        assert(1, isFalse());
    }
}
