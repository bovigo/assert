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
 * Tests for bovigo\assert\predicate\Regex.
 */
#[Group('predicate')]
class RegexTest extends TestCase
{
    public static function provideValidValues(): iterable
    {
        yield ['/^([a-z]{3})$/', 'foo'];
        yield ['/^([a-z]{3})$/i', 'foo'];
        yield ['/^([a-z]{3})$/i', 'Bar'];
    }

    #[Test]
    #[DataProvider('provideValidValues')]
    public function validValueEvaluatesToTrue(string $pattern, string $value): void
    {
        assertTrue(matches($pattern)->test($value));
    }

    public static function provideInvalidValues(): iterable
    {
        yield ['/^([a-z]{3})$/', 'Bar'];
        yield ['/^([a-z]{3})$/', 'baz0123'];
        yield ['/^([a-z]{3})$/i', 'baz0123'];
    }

    #[Test]
    #[DataProvider('provideInvalidValues')]
    public function invalidValueEvaluatesToFalse(string $pattern, string $value): void
    {
        assertFalse(matches($pattern)->test($value));
    }

    #[Test]
    public function nonStringsThrowInvalidArgumentException(): void
    {
        expect(fn() => matches('/^([a-z]{3})$/')->test(303))
            ->throws(InvalidArgumentException::class)
            ->withMessage(
                'Given value of type "integer" can not be matched against a regular expression.'
            );
    }

    #[Test]
    public function nullThrowInvalidArgumentException(): void
    {
        expect(fn() => matches('/^([a-z]{3})$/')->test(null))
            ->throws(InvalidArgumentException::class)
            ->withMessage(
                'Given value of type "NULL" can not be matched against a regular expression.'
            );
    }

    #[Test]
    public function invalidRegexThrowsRuntimeExceptionOnEvaluation(): void
    {
        $regex = new Regex('^([a-z]{3})$');
        expect(fn() => $regex('foo'))
            ->throws(\RuntimeException::class)
            ->withMessage(
                'Failure while matching "^([a-z]{3})$", reason: internal PCRE error.'
            );
    }

    #[Test]
    public function stringRepresentationContainsRegex(): void
    {
        assertThat(
            (string) new Regex('/^([a-z]{3})$/'),
            equals('matches regular expression "/^([a-z]{3})$/"')
        );
    }

    #[Test]
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat('f', matches('/^([a-z]{3})$/')))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that 'f' matches regular expression \"/^([a-z]{3})$/\"."
            );
    }
}
