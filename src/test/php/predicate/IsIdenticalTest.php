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

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assert(true, isSameAs(false)); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that true is identical to false.");
    }

    /**
     * @test
     */
    public function assertionFailureWithObjectsContainsMeaningfulInformation()
    {
        expect(function() {
            assert(new \stdClass(), isSameAs(new \stdClass()));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that object of type "stdClass" is identical to object of type "stdClass".'
        );
    }

    /**
     * @test
     */
    public function assertionFailureWithObjectAndOtherContainsMeaningfulInformation()
    {
        expect(function() { assert(new \stdClass(), isSameAs('foo')); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that object of type "stdClass" is identical to \'foo\'.'
        );
    }
}
