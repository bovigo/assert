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

use function bovigo\assert\predicate\{
    contains,
    isInstanceOf,
    isSameAs
};
/**
 * Tests for bovigo\assert\CatchedError.
 *
 * @since  2.1.0
 */
class CatchedErrorTest extends TestCase
{
    private $catchedError;

    public function setup(): void
    {
        $this->catchedError = new CatchedError(E_NOTICE , 'error', __FILE__, 303);
    }

    /**
     * @test
     */
    public function withMessageComparesUsingEquals(): void
    {
        assertThat(
                $this->catchedError->withMessage('error'),
                isInstanceOf(CatchedError::class)
        );
    }

    /**
     * @test
     */
    public function withMessageFailsThrowsAssertionFailure(): void
    {
        expect(function() {
                $this->catchedError->withMessage('failure');
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that error message 'error' is equal to <string:failure>.
--- Expected
+++ Actual
@@ @@
-'failure'
+'error'
"
        );
    }

    /**
     * @test
     */
    public function messageAssertsWithGivenPredicate(): void
    {
        assertThat(
                $this->catchedError->message(contains('err')),
                isInstanceOf(CatchedError::class)
        );
    }

    /**
     * @test
     * @since  5.0.1
     */
    public function messageAssertsWithGivenCallable(): void
    {
        assertThat(
                $this->catchedError->message('is_string'),
                isInstanceOf(CatchedError::class)
        );
    }

    /**
     * @test
     */
    public function messageAssertsWithGivenPredicateThrowsAssertionFailureWhenPredicateFails(): void
    {
        expect(function() {
                $this->catchedError->message(contains('fail'));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that error message 'error' contains 'fail'."
        );
    }

    /**
     * @test
     */
    public function afterExecutesGivenPredicateWithGivenValue(): void
    {
        $this->catchedError->after(
                $this->catchedError,
                isSameAs($this->catchedError)
        );
    }
}
