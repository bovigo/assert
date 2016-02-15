<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use function bovigo\assert\predicate\contains;
use function bovigo\assert\predicate\isInstanceOf;
use function bovigo\assert\predicate\isSameAs;
/**
 * Tests for bovigo\assert\CatchedException.
 *
 * @since  1.6.0
 */
class CatchedExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @type  \bovigo\assert\CatchedException
     */
    private $catchedException;
    /**
     * @type  \Exception
     */
    private $exception;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->exception        = new \BadFunctionCallException('failure', 2);
        $this->catchedException = new CatchedException($this->exception);
    }

    /**
     * @test
     */
    public function withMessageComparesUsingEquals()
    {
        assert(
                $this->catchedException->withMessage('failure'),
                isInstanceOf(CatchedException::class)
        );
    }

    /**
     * @test
     */
    public function withMessageFailsThrowsAssertionFailure()
    {
        expect(function() {
                $this->catchedException->withMessage('error');
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that exception message 'failure' is equal to <string:error>.
--- Expected
+++ Actual
@@ @@
-'error'
+'failure'
"
        );
    }

    /**
     * @test
     */
    public function messageAssertsWithGivenPredicate()
    {
        assert(
                $this->catchedException->message(contains('fail')),
                isInstanceOf(CatchedException::class)
        );
    }

    /**
     * @test
     */
    public function messageAssertsWithGivenPredicateThrowsAssertionFailureWhenPredicateFails()
    {
        expect(function() {
                $this->catchedException->message(contains('error'));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that exception message 'failure' contains 'error'."
        );
    }

    /**
     * @test
     */
    public function withCodeComparesUsingEquals()
    {
        assert(
                $this->catchedException->withCode(2),
                isInstanceOf(CatchedException::class)
        );
    }

    /**
     * @test
     */
    public function withCodeFailsThrowsAssertionFailure()
    {
        expect(function() {
                $this->catchedException->withCode(3);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that exception code 2 is equal to 3."
        );
    }

    /**
     * @test
     */
    public function withAppliesPredicateToException()
    {
        $this->catchedException->with(
                function(\Exception $e)
                {
                    return assert($e, isSameAs($this->exception));
                }
        );
    }

    /**
     * @test
     */
    public function withReturnsSelfOnSuccess()
    {
        assert(
                $this->catchedException->with(function() { return true; }),
                isSameAs($this->catchedException)
        );
    }

    /**
     * @test
     */
    public function afterExecutesGivenPredicateWithGivenValue()
    {
        $this->catchedException->after(
                $this->catchedException,
                isSameAs($this->catchedException)
        );
    }
}
