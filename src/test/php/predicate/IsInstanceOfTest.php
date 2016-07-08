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

use function bovigo\assert\assert;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsInstanceOf.
 *
 * @group  predicate
 */
class IsInstanceOfTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionWhenGivenExpectedTypeIsUnknown()
    {
        expect(function() { isInstanceOf('DoesNotExist'); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfGivenValueIsInstanceOfExpectedType()
    {
        assert(isInstanceOf(__CLASS__)->test($this), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfGivenValueIsNotInstanceOfExpectedType()
    {
        assert(isInstanceOf('\stdClass')->test($this), isFalse());
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {

        expect(function() { assert([], isInstanceOf('\stdClass')); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that an array is an instance of class "\stdClass".'
        );
    }

    /**
     * @test
     */
    public function assertionFailureWithObjectsContainsMeaningfulInformation()
    {
        expect(function() { assert(new self(), isInstanceOf('\stdClass')); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that ' . IsInstanceOfTest::class
                        . ' Object (...) is an instance of class "\stdClass".'
        );
    }
}
