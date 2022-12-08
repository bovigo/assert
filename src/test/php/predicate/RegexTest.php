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
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\Regex.
 *
 * @group  predicate
 */
class RegexTest extends TestCase
{
    public function validValues(): Generator
    {
        yield ['/^([a-z]{3})$/', 'foo'];
        yield ['/^([a-z]{3})$/i', 'foo'];
        yield ['/^([a-z]{3})$/i', 'Bar'];
    }

    /**
     * @test
     * @dataProvider  validValues
     */
    public function validValueEvaluatesToTrue(string $pattern, string $value): void
    {
        assertTrue(matches($pattern)->test($value));
    }

    /**
     * @return  array<array<string>>
     */
    public function invalidValues(): Generator
    {
        yield ['/^([a-z]{3})$/', 'Bar'];
        yield ['/^([a-z]{3})$/', 'baz0123'];
        yield ['/^([a-z]{3})$/i', 'baz0123'];
    }

    /**
     * @test
     * @dataProvider  invalidValues
     */
    public function invalidValueEvaluatesToFalse(string $pattern, string $value): void
    {
        assertFalse(matches($pattern)->test($value));
    }

    /**
     * @test
     */
    public function nonStringsThrowInvalidArgumentException(): void
    {
        expect(fn() => matches('/^([a-z]{3})$/')->test(303))
            ->throws(InvalidArgumentException::class)
            ->withMessage(
                'Given value of type "integer" can not be matched against a regular expression.'
            );
    }

    /**
     * @test
     */
    public function nullThrowInvalidArgumentException(): void
    {
        expect(fn() => matches('/^([a-z]{3})$/')->test(null))
            ->throws(InvalidArgumentException::class)
            ->withMessage(
                'Given value of type "NULL" can not be matched against a regular expression.'
            );
    }

    /**
     * @test
     */
    public function invalidRegexThrowsRuntimeExceptionOnEvaluation(): void
    {
        $regex = new Regex('^([a-z]{3})$');
        expect(fn() => $regex('foo'))
            ->throws(\RuntimeException::class)
            ->withMessage(
                'Failure while matching "^([a-z]{3})$", reason: internal PCRE error.'
            );
    }

    /**
     * @test
     */
    public function stringRepresentationContainsRegex(): void
    {
        assertThat(
            (string) new Regex('/^([a-z]{3})$/'),
            equals('matches regular expression "/^([a-z]{3})$/"')
        );
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat('f', matches('/^([a-z]{3})$/')))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that 'f' matches regular expression \"/^([a-z]{3})$/\"."
            );
    }
}
