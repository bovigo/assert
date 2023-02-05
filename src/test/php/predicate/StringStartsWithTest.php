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
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\StringStartsWith.
 *
 * @since 1.1.0
 */
#[Group('predicate')]
class StringStartsWithTest extends TestCase
{
    #[Test]
    public function nonStringValuesThrowInvalidArgumentException(): void
    {
        expect(fn() => startsWith('foo')->test(303))
                ->throws(InvalidArgumentException::class);
    }

    public static function trueValues(): Generator
    {
        yield 'string which starts with and contains foo' => ['foobarfoobaz'];
        yield'string which starts with foo'              => ['foobarbaz'];
    }

    #[Test]
    #[DataProvider('trueValues')]
    public function evaluatesToTrueIfStringStartsWithPrefix(string $value): void
    {
        assertTrue(startsWith('foo')->test($value));
    }

    public static function falseValues(): Generator
    {
        yield'string which contains foo'  => ['barfoobaz'];
        yield'string which ends with foo' => ['barbazfoo'];
    }

    #[Test]
    #[DataProvider('falseValues')]
    public function evaluatesToFalseIfGivenValueIsFalse(string $value): void
    {
        assertFalse(startsWith('foo')->test($value));
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat('bar', startsWith('foo')))
            ->throws(AssertionFailure::class)
            ->withMessage("Failed asserting that 'bar' starts with 'foo'.");
    }
}
