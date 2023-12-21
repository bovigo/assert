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
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
/**
 * Tests for bovigo\assert\*().
 *
 * @since  1.2.0
 */
#[Group('assert')]
class AssertTest extends TestCase
{
    #[Test]
    public function assertSucceedsWhenPredicateReturnsTrue(): void
    {
        assertThat(assertThat('some value', fn() => true ), isTrue());
    }

    #[Test]
    public function assertFailsWhenPredicateReturnsFalse(): void
    {
        expect(fn() => assertThat('some value', fn() => false ))
            ->throws(AssertionFailure::class)
            ->withMessage(
                    "Failed asserting that 'some value' satisfies a lambda function."
            );
    }

    #[Test]
    public function assertionFailureContainsAdditionalDescription(): void
    {
        expect(fn() => assertThat('some value', fn() => false, 'some more info'))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that \'some value\' satisfies a lambda function.
some more info'
            );
    }

    #[Test]
    public function failThrowsAssertionFailure(): void
    {
        expect(fn() => fail('Fail test hard.'))
            ->throws(AssertionFailure::class)
            ->withMessage('Fail test hard.');
    }

    #[Test]
    public function exporterAlwaysReturnsSameInstance(): void
    {
        assertThat(exporter(), isSameAs(exporter()));
    }

    #[Test]
    public function assertionCounterIsIncreasedByAmountOfPredicatesUsedForAssertion(): void
    {
        if (!class_exists('\PHPUnit\Framework\Assert')) {
            $this->markTestSkipped('Can not test this without PHPUnit');
        }

        $countBeforeAssertion = \PHPUnit\Framework\Assert::getCount();
        assertThat('some value', fn() => true );
        assertThat(
            \PHPUnit\Framework\Assert::getCount(),
            equals($countBeforeAssertion + 1)
        );
    }

    #[Test]
    public function assertionCounterIsIncreasedInCaseOfFailure(): void
    {
        if (!class_exists('\PHPUnit\Framework\Assert')) {
            $this->markTestSkipped('Can not test this without PHPUnit');
        }

        $countBeforeAssertion = \PHPUnit\Framework\Assert::getCount();
        expect(fn() => assertThat('some value', fn() => false, 'some more info'))
            ->throws(AssertionFailure::class)
            ->after(
                \PHPUnit\Framework\Assert::getCount(),
                equals($countBeforeAssertion + 2) // one for assertThat(), one for throws()
            );
    }

    /**
     * @since  1.5.0
     */
    #[Test]
    public function assertEmptyStringIsTrueWhenValueIsEmptyString(): void
    {
        assertTrue(assertEmptyString(''));
    }

    #[Test]
    public function assertEmptyStringFailsWhenValueIsNotEmptyString(): void
    {
        expect(fn() => assertEmptyString('foo'))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that 'foo' is an empty string.
--- Expected
+++ Actual
@@ @@
-''
+'foo'
"
            );
    }

    /**
     * @since  1.5.0
     */
    #[Test]
    public function assertEmptyArrayIsTrueWhenValueIsEmptyArray(): void
    {
        assertTrue(assertEmptyArray([]));
    }

    /**
     * @since  1.5.0
     */
    #[Test]
    public function assertEmptyArrayFailsWhenValueIsNotEmptyArray(): void
    {
        expect(fn() => assertEmptyArray(['foo']))
            ->throws(AssertionFailure::class)
            ->message(startsWith(
                'Failed asserting that an array is an empty array.
--- Expected
+++ Actual
@@ @@
 Array (
+    0 => \'foo\'
'
            ));
    }

    /**
     * @since  2.1.0
     */
    #[Test]
    #[Group('issue_3')]
    public function outputOfReturnsTrueOnSuccess(): void
    {
        assertTrue(
            outputOf(
                fn() => print 'Hello world!',
                equals('Hello world!')
            )
        );
    }

    /**
     * @since  2.1.0
     */
    #[Test]
    #[Group('issue_3')]
    public function outputOfThrowsAssertionFailureWhenOutputDoesSatisfyPredicate(): void
    {
        expect(fn() => outputOf(
            fn() => print 'Hello you!',
            equals('Hello world!'),
            'So be it'
        ))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that 'Hello you!' is equal to <string:Hello world!>.
--- Expected
+++ Actual
@@ @@
-'Hello world!'
+'Hello you!'

So be it"
            );
    }
}
