<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
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
class CatchedErrorTest extends \PHPUnit_Framework_TestCase
{
    private $catchedError;

    public function setup()
    {
        $this->catchedError = new CatchedError(E_NOTICE , 'error', __FILE__, 303);
    }

    /**
     * @test
     */
    public function withMessageComparesUsingEquals()
    {
        assert(
                $this->catchedError->withMessage('error'),
                isInstanceOf(CatchedError::class)
        );
    }

    /**
     * @test
     */
    public function withMessageFailsThrowsAssertionFailure()
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
    public function messageAssertsWithGivenPredicate()
    {
        assert(
                $this->catchedError->message(contains('err')),
                isInstanceOf(CatchedError::class)
        );
    }

    /**
     * @test
     */
    public function messageAssertsWithGivenPredicateThrowsAssertionFailureWhenPredicateFails()
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
    public function afterExecutesGivenPredicateWithGivenValue()
    {
        $this->catchedError->after(
                $this->catchedError,
                isSameAs($this->catchedError)
        );
    }
}
