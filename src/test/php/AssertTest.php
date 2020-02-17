<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\predicate\equals;
use function bovigo\assert\predicate\isSameAs;
use function bovigo\assert\predicate\isTrue;
use function bovigo\assert\predicate\startsWith;
/**
 * Tests for bovigo\assert\*().
 *
 * @group  assert
 * @since  1.2.0
 */
class AssertTest extends TestCase
{
    /**
     * @test
     */
    public function assertSucceedsWhenPredicateReturnsTrue(): void
    {
        assertThat(assertThat('some value', function() { return true; }), isTrue());
    }

    /**
     * @test
     */
    public function assertFailsWhenPredicateReturnsFalse(): void
    {
        expect(function() {
            assertThat('some value', function() { return false; });
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that 'some value' satisfies a lambda function."
        );
    }

    /**
     * @test
     */
    public function assertionFailureContainsAdditionalDescription(): void
    {
        expect(function() {
                assertThat(
                        'some value',
                        function() { return false; },
                        'some more info'
                );
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                    'Failed asserting that \'some value\' satisfies a lambda function.'
                    . PHP_EOL
                    .'some more info'
        );
    }

    /**
     * @test
     */
    public function failThrowsAssertionFailure(): void
    {
        expect(function() {
            fail('Fail test hard.');
        })
        ->throws(AssertionFailure::class)
        ->withMessage('Fail test hard.');
    }

    /**
     * @test
     */
    public function exporterAlwaysReturnsSameInstance(): void
    {
        assertThat(exporter(), isSameAs(exporter()));
    }

    /**
     * @test
     */
    public function assertionCounterIsIncreasedByAmountOfPredicatesUsedForAssertion(): void
    {
        if (!class_exists('\PHPUnit\Framework\Assert')) {
            $this->markTestSkipped('Can not test this without PHPUnit');
        }

        $countBeforeAssertion = \PHPUnit\Framework\Assert::getCount();
        assertThat('some value', function() { return true; });
        assertThat(
                \PHPUnit\Framework\Assert::getCount(),
                equals($countBeforeAssertion + 1)
        );
    }

    /**
     * @test
     */
    public function assertionCounterIsIncreasedInCaseOfFailure(): void
    {
        if (!class_exists('\PHPUnit\Framework\Assert')) {
            $this->markTestSkipped('Can not test this without PHPUnit');
        }

        $countBeforeAssertion = \PHPUnit\Framework\Assert::getCount();
        expect(function() {
            assertThat('some value', function() { return false; }, 'some more info');
        })
        ->throws(AssertionFailure::class)
        ->after(
                \PHPUnit\Framework\Assert::getCount(),
                equals($countBeforeAssertion + 2) // one for assertThat(), one for throws()
        );
    }

    /**
     * @test
     * @since  1.5.0
     */
    public function assertEmptyStringIsTrueWhenValueIsEmptyString(): void
    {
        assertTrue(assertEmptyString(''));
    }

    /**
     * @test
     */
    public function assertEmptyStringFailsWhenValueIsNotEmptyString(): void
    {
        expect(function() { assertEmptyString('foo'); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 'foo' is an empty string."
                        . PHP_EOL
                        . '--- Expected'
                        . PHP_EOL
                        . '+++ Actual'
                        . PHP_EOL
                        . '@@ @@'
                        . PHP_EOL
                        . "-''"
                        . PHP_EOL
                        . "+'foo'"
                        . PHP_EOL
                );
    }

    /**
     * @test
     * @since  1.5.0
     */
    public function assertEmptyArrayIsTrueWhenValueIsEmptyArray(): void
    {
        assertTrue(assertEmptyArray([]));
    }

    /**
     * @test
     * @since  1.5.0
     */
    public function assertEmptyArrayFailsWhenValueIsNotEmptyArray(): void
    {
        expect(function() { assertEmptyArray(['foo']); })
                ->throws(AssertionFailure::class)
                ->message(startsWith(
                        'Failed asserting that an array is an empty array.'
                        . PHP_EOL
                        . '--- Expected'
                        . PHP_EOL
                        . '+++ Actual'
                        . PHP_EOL
                        . '@@ @@'
                        . PHP_EOL
                        . ' Array ('
                            . PHP_EOL
                            . '+    0 => \'foo\''
                            . PHP_EOL
        ));
    }

    /**
     * @test
     * @group  issue_3
     * @since  2.1.0
     */
    public function outputOfReturnsTrueOnSuccess(): void
    {
        assertTrue(
                outputOf(
                        function() { echo 'Hello world!'; },
                        equals('Hello world!')
                )
        );
    }

    /**
     * @test
     * @group  issue_3
     * @since  2.1.0
     */
    public function outputOfThrowsAssertionFailureWhenOutputDoesSatisfyPredicate(): void
    {
        expect(function() {
                outputOf(
                        function() { echo 'Hello you!'; },
                        equals('Hello world!'),
                        'So be it'
                );
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that 'Hello you!' is equal to <string:Hello world!>."
                . PHP_EOL
                . '--- Expected'
                . PHP_EOL
                . '+++ Actual'
                . PHP_EOL
                . '@@ @@'
                . PHP_EOL
                . "-'Hello world!'"
                . PHP_EOL
                . "+'Hello you!'"
                . PHP_EOL
                . PHP_EOL
                . 'So be it'
        );
    }
}
