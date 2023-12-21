<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;

use PHPUnit\Framework\Attributes\Test;
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
    /**
     * @var  CatchedError
     */
    private $catchedError;

    public function setup(): void
    {
        $this->catchedError = new CatchedError(E_NOTICE , 'error', __FILE__, 303);
    }

    #[Test]
    public function withMessageComparesUsingEquals(): void
    {
        assertThat(
            $this->catchedError->withMessage('error'),
            isInstanceOf(CatchedError::class)
        );
    }

    #[Test]
    public function withMessageFailsThrowsAssertionFailure(): void
    {
        expect(fn() => $this->catchedError->withMessage('failure'))
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

    #[Test]
    public function messageAssertsWithGivenPredicate(): void
    {
        assertThat(
            $this->catchedError->message(contains('err')),
            isInstanceOf(CatchedError::class)
        );
    }

    /**
     * @since  5.0.1
     */
    #[Test]
    public function messageAssertsWithGivenCallable(): void
    {
        assertThat(
            $this->catchedError->message('is_string'),
            isInstanceOf(CatchedError::class)
        );
    }

    #[Test]
    public function messageAssertsWithGivenPredicateThrowsAssertionFailureWhenPredicateFails(): void
    {
        expect(fn() => $this->catchedError->message(contains('fail')))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that error message 'error' contains 'fail'."
            );
    }

    #[Test]
    public function afterExecutesGivenPredicateWithGivenValue(): void
    {
        $this->catchedError->after($this->catchedError, isSameAs($this->catchedError));
    }
}
