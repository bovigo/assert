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

use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsTrue.
 */
#[Group('predicate')]
class IsTrueTest extends TestCase
{
    #[Test]
    public function evaluatesToTrueIfGivenValueIsTrue(): void
    {
        assertThat(isTrue()->test(true), isSameAs(true));
    }

    public static function provideFalseValues(): iterable
    {
        yield 'boolean false'    => [false];
        yield 'non-empty string' => ['foo'];
        yield 'empty string'     => [''];
        yield 'empty array'      => [[]];
        yield 'non-empty array'  => [[1]];
    }

    #[Test]
    #[DataProvider('provideFalseValues')]
    public function evaluatesToFalseIfGivenValueIsFalse(mixed $false): void
    {
        assertThat(isTrue()->test($false), isSameAs(false));
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertTrue([]))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that an array is true.");
    }
}
