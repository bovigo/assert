<?php
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
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'some value' satisfies a lambda function.
     */
    public function assertFailsWhenPredicateReturnsFalse()
    {
        assert('some value', function() { return false; });
    }

    /**
     * @test
     */
    public function assertionFailureContainsAdditionalDescription()
    {
        try {
            assert('some value', function() { return false; }, 'some more info');
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that \'some value\' satisfies a lambda function.
some more info')
            );
            return;
        }

        fail('Expected ' . AssertionFailure::class . ', gone none');
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Fail test hard.
     */
    public function failThrowsAssertionFailure()
    {
        fail('Fail test hard.');
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
        try {
            assert('some value', function() { return false; }, 'some more info');
        } catch (AssertionFailure $af) {
            assert(
                    \PHPUnit_Framework_Assert::getCount(),
                    equals($countBeforeAssertion + 1)
            );
            return;
        }

        fail('Expected ' . AssertionFailure::class . ', gone none');
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
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'foo' is an empty string.
     */
    public function assertEmptyStringFailsWhenValueIsNotEmptyString()
    {
        assertEmptyString('foo');
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
        try {
            assertEmptyArray(['foo']);
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that an array is an empty array.
--- Expected
+++ Actual
@@ @@
 Array (
+    0 => \'foo\'
 )
')
            );
            return;
        }

        fail('Expected ' . AssertionFailure::class . ', gone none');
    }
}
