<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert\AssertionFailure;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertNotNull;
use function bovigo\assert\assertNull;
use function bovigo\assert\assertTrue;
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
        assertTrue(isNull()->test(null));
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
        assertFalse(isNull()->test($nonNullValue));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        assert(
                function() { assertNull([]); },
                throws(AssertionFailure::class)->withMessage(
                        "Failed asserting that an array is null."
                )
        );
    }

    /**
     * @test
     * @since  1.3.0
     */
    public function aliasAssertNotNull()
    {
        assertTrue(assertNotNull(303));
    }
}
