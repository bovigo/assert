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
use bovigo\assert\predicate\IsInstanceOf;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsInstanceOf.
 */
#[Group('predicate')]
class IsInstanceOfTest extends TestCase
{
    #[Test]
    public function throwsInvalidArgumentExceptionWhenGivenExpectedTypeIsUnknown(): void
    {
        expect(fn() => isInstanceOf('DoesNotExist'))
            ->throws(InvalidArgumentException::class);
    }

    #[Test]
    public function evaluatesToTrueIfGivenValueIsInstanceOfExpectedType(): void
    {
        assertTrue(isInstanceOf(__CLASS__)->test($this));
    }

    #[Test]
    public function evaluatesToFalseIfGivenValueIsNotInstanceOfExpectedType(): void
    {
        assertFalse(isInstanceOf(stdClass::class)->test($this));
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {

        expect(fn() => assertThat([], isInstanceOf('\stdClass')))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that an array is an instance of class "\stdClass".'
            );
    }

    #[Test]
    public function assertionFailureWithObjectsContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat(new IsInstanceOf(self::class), isInstanceOf(stdClass::class)))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that ' . IsInstanceOf::class
                . ' Object (...) is an instance of class "stdClass".'
            );
    }
}
