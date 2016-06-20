<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use bovigo\assert\predicate\ExpectedException;

use function bovigo\assert\predicate\isFalse;
use function bovigo\assert\predicate\isInstanceOf;
use function bovigo\assert\predicate\isSameAs;
use function bovigo\assert\predicate\isTrue;
/**
 * Tests for bovigo\assert\expect().
 *
 * @since  1.6.0
 */
class ExpectationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function expectationReturnsCatchedExceptionWhenThrowsSucceeds()
    {
        assert(
            expect(function(){
                throw new \Exception();
            })
            ->throws(),
            isInstanceOf(CatchedException::class)
        );
    }

    /**
     * @test
     */
    public function expectationReturnsItselfWhenDoesNotThrowSucceeds()
    {
        $expectation = expect(function(){ /* intentionally empty */});
        assert($expectation->doesNotThrow(), isSameAs($expectation));
    }

    /**
     * @test
     */
    public function expectationThrowsAssertionFailureWhenCodeDoesNotThrowAnyExpectedException()
    {
        expect(function(){
            expect(function(){
                // intentionally empty
            })
            ->throws();
        })
        ->throws(AssertionFailure::class)
        ->withMessage('Failed asserting that an exception is thrown.');
    }

    /**
     * @test
     */
    public function expectationThrowsAssertionFailureWhenCodeDoesNotThrowExpectedExceptionType()
    {
        expect(function(){
            expect(function(){
                // intentionally empty
            })
            ->throws(\BadFunctionCallException::class);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that exception of type "'
                . \BadFunctionCallException::class
                . '" is thrown.'
        );
    }

    /**
     * @test
     */
    public function expectationDoesNotThrowAssertionFailureWhenCodeThrowsAnyExpectedException()
    {
        expect(function(){
            expect(function(){
                throw new \BadFunctionCallException('error');
            })
            ->throws();
        })
        ->doesNotThrow();
    }

    /**
     * @test
     */
    public function expectationDoesNotThrowAssertionFailureWhenCodeThrowsExpectedExceptionType()
    {
        expect(function(){
            expect(function(){
                throw new \BadFunctionCallException('error');
            })
            ->throws(\BadFunctionCallException::class);
        })
        ->doesNotThrow();
    }

    /**
     * @test
     */
    public function expectationDoesNotThrowAssertionFailureIfCodeDoesNotThrowAnyException()
    {
        expect(function(){
            expect(function(){
                // intentionally empty
            })
            ->doesNotThrow();
        })
        ->doesNotThrow();
    }

    /**
     * @test
     */
    public function expectationDoesNotThrowAssertionFailureIfCodeDoesNotThrowExpectedExceptionType()
    {
        expect(function(){
            expect(function(){
                // intentionally empty
            })
            ->doesNotThrow(\BadFunctionCallException::class);
        })
        ->doesNotThrow();
    }

    /**
     * @test
     */
    public function expectationDoesThrowAssertionFailureWhenCodeThrowsUnexpectedException()
    {
        expect(function(){
            expect(function(){
                throw new \BadFunctionCallException('error');
            })
            ->doesNotThrow();
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that no exception is thrown, got '
                . \BadFunctionCallException::class
                . ' with message "error".'
        );
    }

    /**
     * @test
     */
    public function expectationDoesThrowAssertionFailureWhenCodeThrowsUnexpectedExceptionType()
    {
        expect(function(){
            expect(function(){
                throw new \BadFunctionCallException('error');
            })
            ->doesNotThrow(\BadFunctionCallException::class);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that no exception of type "'
                . \BadFunctionCallException::class
                . '" is thrown, got '
                . \BadFunctionCallException::class
                . ' with message "error".'
        );
    }

    /**
     * @test
     */
    public function expectationDoesNotThrowAssertionFailureWhenCodeThrowsOtherExceptionType()
    {
        expect(function(){
            expect(function(){
                throw new \BadFunctionCallException('error');
            })
            ->doesNotThrow(\BadMethodCallException::class);
        })
        ->doesNotThrow();
    }

    /**
     * @test
     */
    public function expectationReturnsItselfWhenResultCheckSucceeds()
    {
        $expectation = expect(function(){ return true; });
        assert($expectation->result(isTrue()), isSameAs($expectation));
    }

    /**
     * @test
     */
    public function expectationDoesNotThrowAssertionFailureWhenResultFulfillsPredicate()
    {
        expect(function(){
            expect(function(){
                return true;
            })
            ->result(isTrue());
        })
        ->doesNotThrow();
    }

    /**
     * @test
     */
    public function expectationThrowsAssertionFailureWhenResultDoesNotFulfillPredicate()
    {
        expect(function(){
            expect(function(){
                return true;
            })
            ->result(isFalse());
        })
        ->throws(AssertionFailure::class)
        ->withMessage('Failed asserting that true is false.');
    }

    /**
     * @test
     */
    public function expectationThrowsAssertionFailureWhenResultNotAvailableBecauseCodeThrowsException()
    {
        expect(function(){
            expect(function(){
                throw new \BadFunctionCallException('error');
            })
            ->result(isTrue());
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that result is true because exception of type '
                . \BadFunctionCallException::class
                . ' with message "error" was thrown.'
        );
    }

    /**
     * @test
     */
    public function expectationThrowsAssertionFailureWhenResultNotAvailableBecauseCodeThrowsExceptionWithCallable()
    {
        expect(function(){
            expect(function(){
                throw new \BadFunctionCallException('error');
            })
            ->result('is_int');
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that result satisfies is_int() because exception of type '
                . \BadFunctionCallException::class
                . ' with message "error" was thrown.'
        );
    }

    /**
     * @test
     */
    public function expectationCanAssertAfterCodeExecution()
    {
        $expectation = expect(function(){ return false; });
        assert($expectation->after(true, isTrue()), isSameAs($expectation));
    }

    /**
     * @test
     */
    public function expectationCanAssertAfterCodeExecutionEvenIfExceptionThrown()
    {
        $expectation = expect(function(){
                throw new \BadFunctionCallException('error');
        });
        assert($expectation->after(true, isTrue()), isSameAs($expectation));
    }

    /**
     * @test
     */
    public function codeIsOnlyExecutedOnce()
    {
        $expectation = expect(function(){
                static $count = 0;
                $count++;
                if (1 !== $count) {
                    throw new \BadFunctionCallException('Called more than once');
                }

                return true;
        });
        expect(function() use ($expectation) {
            $expectation->result(isTrue())->result(isTrue());
        })
        ->doesNotThrow();
    }

    /**
     * @test
     */
    public function expectedExceptionThrowsInvalidArgumentExceptionWhenValueToTestIsNotAnException()
    {
        expect(function() {
            $expectedException = new ExpectedException(\Exception::class);
            $expectedException->test(404);
        })
        ->throws(\InvalidArgumentException::class)
        ->withMessage('Given value is not an exception');
    }

    /**
     * @test
     * @group  issue_1
     * @since  1.6.1
     */
    public function outputOfUnexpectedExceptionIsHelpful()
    {
        expect(function() {
                expect(function() { throw new \LogicException('error'); })
                        ->throws(\BadMethodCallException::class);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that exception of type "'
                . \LogicException::class
                . '" with message "error" thrown in ' . __FILE__
                . ' on line 326 matches expected exception "'
                . \BadMethodCallException::class . '".'
        );
    }
}
