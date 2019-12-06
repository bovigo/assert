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

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsInstanceOf.
 *
 * @group  predicate
 */
class IsInstanceOfTest extends TestCase
{
    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionWhenGivenExpectedTypeIsUnknown(): void
    {
        expect(function() { isInstanceOf('DoesNotExist'); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfGivenValueIsInstanceOfExpectedType(): void
    {
        assertTrue(isInstanceOf(__CLASS__)->test($this));
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfGivenValueIsNotInstanceOfExpectedType(): void
    {
        assertFalse(isInstanceOf('\stdClass')->test($this));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {

        expect(function() { assertThat([], isInstanceOf('\stdClass')); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that an array is an instance of class "\stdClass".'
        );
    }

    /**
     * @test
     */
    public function assertionFailureWithObjectsContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat(new self(), isInstanceOf('\stdClass')); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that ' . IsInstanceOfTest::class
                        . ' Object (...) is an instance of class "\stdClass".'
        );
    }
}
