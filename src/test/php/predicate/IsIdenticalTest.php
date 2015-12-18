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
 * Tests for bovigo\assert\predicate\IsIdentical.
 *
 * @group  predicate
 */
class IsIdenticalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return  array
     */
    public function identicalValues()
    {
        return [
            'boolean true'  => [true],
            'boolean false' => [false],
            'string'        => ['foo'],
            'number'        => [303],
            'object'        => [new \stdClass()],
            'float'         => [3.03]
        ];
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  identicalValues
     */
    public function evaluatesToTrueIfGivenValueIsIdentical($value)
    {
        assert(isSameAs($value)->test($value), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfGivenValueIsNotIdentical()
    {
        assert(isSameAs(3.03)->test(3.02), isFalse());
    }
}
