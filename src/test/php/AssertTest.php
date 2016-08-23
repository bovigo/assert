<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use function bovigo\assert\predicate\equals;
use function bovigo\assert\predicate\isSameAs;
use function bovigo\assert\predicate\isTrue;
/**
 * Tests for bovigo\assert\*().
 *
 * @group  assert
 * @since  1.2.0
 */
class AssertTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function assertSucceedsWhenPredicateReturnsTrue()
    {
        assert(assert('some value', function() { return true; }), isTrue());
    }

    /**
     * @test
     */
    public function assertFailsWhenPredicateReturnsFalse()
    {
        expect(function() {
            assert('some value', function() { return false; });
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that 'some value' satisfies a lambda function."
        );
    }

    /**
     * @test
     */
    public function assertionFailureContainsAdditionalDescription()
    {
        expect(function() {
                assert(
                        'some value',
                        function() { return false; },
                        'some more info'
                );
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                    'Failed asserting that \'some value\' satisfies a lambda function.
some more info'
        );
    }

    /**
     * @test
     */
    public function failThrowsAssertionFailure()
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
    public function exporterAlwaysReturnsSameInstance()
    {
        assert(exporter(), isSameAs(exporter()));
    }

    /**
     * @test
     */
    public function assertionCounterIsIncreasedByAmountOfPredicatesUsedForAssertion()
    {
        if (!class_exists('\PHPUnit_Framework_Assert')) {
            $this->skip('Can not test this without PHPUnit');
        }

        $countBeforeAssertion = \PHPUnit_Framework_Assert::getCount();
        assert('some value', function() { return true; });
        assert(
                \PHPUnit_Framework_Assert::getCount(),
                equals($countBeforeAssertion + 1)
        );
    }

    /**
     * @test
     */
    public function assertionCounterIsIncreasedInCaseOfFailure()
    {
        if (!class_exists('\PHPUnit_Framework_Assert')) {
            $this->skip('Can not test this without PHPUnit');
        }

        $countBeforeAssertion = \PHPUnit_Framework_Assert::getCount();
        expect(function() {
            assert('some value', function() { return false; }, 'some more info');
        })
        ->throws(AssertionFailure::class)
        ->after(
                \PHPUnit_Framework_Assert::getCount(),
                equals($countBeforeAssertion + 2) // one for assert(), one for throws()
        );
    }

    /**
     * @test
     * @since  1.5.0
     */
    public function assertEmptyStringIsTrueWhenValueIsEmptyString()
    {
        assertTrue(assertEmptyString(''));
    }

    /**
     * @test
     */
    public function assertEmptyStringFailsWhenValueIsNotEmptyString()
    {
        expect(function() { assertEmptyString('foo'); })
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
     * @test
     * @since  1.5.0
     */
    public function assertEmptyArrayIsTrueWhenValueIsEmptyArray()
    {
        assertTrue(assertEmptyArray([]));
    }

    /**
     * @test
     * @since  1.5.0
     */
    public function assertEmptyArrayFailsWhenValueIsNotEmptyArray()
    {
        expect(function() { assertEmptyArray(['foo']); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        'Failed asserting that an array is an empty array.
--- Expected
+++ Actual
@@ @@
 Array (
+    0 => \'foo\'
 )
'
        );
    }

    /**
     * @test
     * @group  issue_3
     * @since  2.1.0
     */
    public function outputOfReturnsTrueOnSuccess()
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
    public function outputOfThrowsAssertionFailureWhenOutputDoesSatisfyPredicate()
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
