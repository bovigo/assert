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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsFalse.
 */
#[Group('predicate')]
class IsFalseTest extends TestCase
{
    #[Test]
    public function evaluatesToTrueIfGivenValueIsFalse(): void
    {
        assertThat(isFalse()->test(false), isSameAs(true));
    }

    public static function provideTrueValues(): iterable
    {
        yield 'boolean true'     => [true];
        yield 'non-empty string' => ['foo'];
        yield 'empty string'     => [''];
        yield 'empty array'      => [[]];
        yield 'non-empty array'  => [[1]];
    }

    #[Test]
    #[DataProvider('provideTrueValues')]
    public function evaluatesToFalseIfGivenValueIsFalse(mixed $true): void
    {
        assertThat(isFalse()->test($true), isSameAs(false));
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertFalse(1))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that 1 is false.");
    }
}
