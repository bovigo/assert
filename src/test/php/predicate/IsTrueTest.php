<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert\AssertionFailure;

use function bovigo\assert\assert;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsTrue.
 *
 * @group  predicate
 */
class IsTrueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function evaluatesToTrueIfGivenValueIsTrue()
    {
        assert(isTrue()->test(true), isSameAs(true));
    }

    /**
     * @return  array
     */
    public function falseValues()
    {
        return [
          'boolean false'    => [false],
          'non-empty string' => ['foo'],
          'empty string'     => [''],
          'empty array'      => [[]],
          'non-empty array'  => [[1]]
        ];
    }

    /**
     * @param  mixed  $false
     * @test
     * @dataProvider  falseValues
     */
    public function evaluatesToFalseIfGivenValueIsFalse($false)
    {
        assert(isTrue()->test($false), isSameAs(false));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assert([], isTrue()); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that an array is true.");
    }
}
