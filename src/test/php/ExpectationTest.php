<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use bovigo\assert\predicate\ExpectedError;
use bovigo\assert\predicate\ExpectedException;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\predicate\{
    isFalse,
    isInstanceOf,
    isSameAs,
    isTrue
};
/**
 * Tests for bovigo\assert\expect().
 *
 * @since  1.6.0
 */
class ExpectationTest extends TestCase
{
    /**
     * @return  array<string,array<mixed>>
     */
    public static function throwables(): array
    {
        return ['exception against exception' => [new \Exception('not catched', 2), \BadFunctionCallException::class],
                'exception against error'     => [new \Exception('not catched', 2), \TypeError::class],
                'error against error'         => [new \Error('not catched', 2), \TypeError::class],
                'error against exception'     => [new \Error('not catched', 2), \BadFunctionCallException::class]
        ];
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationReturnsCatchedExceptionWhenThrowsSucceeds(\Throwable $throwable): void
    {
        assertThat(
                expect(function() use($throwable) { throw $throwable; })->throws(),
                isInstanceOf(CatchedException::class)
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     * @since  2.1.0
     */
    public function expectationThrowsInvalidArgumentExceptionWhenThrowsArgumentIsInvalid(\Throwable $throwable): void
    {
        expect(function() use($throwable) {
                expect(function() use($throwable) { throw $throwable; })->throws(303);
        })
        ->throws(\InvalidArgumentException::class)
        ->withMessage(
                'Given expected is neither a class name nor an instance'
                . ' of \Throwable, but of type integer'
        );
    }

    /**
     * @test
     * @group  issue_5
     * @since  2.1.0
     */
    public function expectationReturnsCatchedErrorWhenTriggersSucceeds(): void
    {
        assertThat(
                expect(function() { trigger_error('error'); })->triggers(),
                isInstanceOf(CatchedError::class)
        );
    }

    /**
     * @test
     * @group  issue_5
     * @since  2.1.0
     */
    public function expectationThrowsInvalidArgumentExceptionWhenExpectingUnknownErrorLevel(): void
    {
        expect(function() {
                expect(function() { /* doesn't matter */ })->triggers(303);
        })
        ->throws(\InvalidArgumentException::class)
        ->withMessage('Unknown error level 303');
    }

    /**
     * @test
     */
    public function expectationReturnsItselfWhenDoesNotThrowSucceeds(): void
    {
        $expectation = expect(function() { /* intentionally empty */});
        assertThat($expectation->doesNotThrow(), isSameAs($expectation));
    }

    /**
     * @test
     */
    public function expectationThrowsAssertionFailureWhenCodeDoesNotThrowAnyExpectedException(): void
    {
        expect(function() {
                expect(function() { /* intentionally empty */ })->throws();
        })
        ->throws(AssertionFailure::class)
        ->withMessage('Failed asserting that an exception is thrown.');
    }

    /**
     * @test
     * @group  issue_5
     * @since  2.1.0
     */
    public function expectationThrowsAssertionFailureWhenCodeDoesNotTriggerAnyExpectedError(): void
    {
        expect(function() {
                expect(function() { /* intentionally empty */ })->triggers();
        })
        ->throws(AssertionFailure::class)
        ->withMessage('Failed asserting that an error is triggered.');
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationThrowsAssertionFailureWhenCodeDoesNotThrowExpectedExceptionType(\Throwable $throwable): void
    {
        expect(function() use($throwable) {
                expect(function() { /* intentionally empty */ })
                        ->throws(get_class($throwable));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that exception of type "'
                . get_class($throwable) . '" is thrown.'
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationThrowsAssertionFailureWhenCodeDoesNotThrowExpectedException(\Throwable $throwable): void
    {
        expect(function() use($throwable) {
                expect(function() { /* intentionally empty */ })
                        ->throws($throwable);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that exception of type "'
                . get_class($throwable) . '" is thrown.'
        );
    }

    /**
     * @test
     * @group  issue_5
     * @since  2.1.0
     */
    public function expectationThrowsAssertionFailureWhenCodeDoesNotTriggerExpectedErrorLevel(): void
    {
        expect(function() {
                expect(function() { /* intentionally empty */ })
                        ->triggers(E_USER_WARNING);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that error of type "E_USER_WARNING" is triggered.'
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationDoesNotThrowAssertionFailureWhenCodeThrowsAnyExpectedException(\Throwable $throwable): void
    {
        expect(function() use($throwable) {
                expect(function() use($throwable) { throw $throwable; })
                        ->throws();
        })
        ->doesNotThrow();
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationDoesNotThrowAssertionFailureWhenCodeThrowsExpectedExceptionType(\Throwable $throwable): void
    {
        expect(function() use($throwable) {
                expect(function() use($throwable) { throw $throwable; })
                        ->throws(get_class($throwable));
        })
        ->doesNotThrow();
    }

    /**
     * @test
     * @group  issue_5
     * @since  2.1.0
     */
    public function expectationDoesNotThrowAssertionFailureWhenCodeTriggersAnyExpectedError(): void
    {
        expect(function() {
                expect(function() { trigger_error('error'); })->triggers();
        })
        ->doesNotThrow();
    }

    /**
     * @test
     * @group  issue_5
     * @since  2.1.0
     */
    public function expectationDoesNotThrowAssertionFailureWhenCodeTriggersExpectedErrorLevel(): void
    {
        expect(function() {
                expect(function() { trigger_error('error', E_USER_WARNING); })
                        ->triggers(E_USER_WARNING);
        })
        ->doesNotThrow();
    }

    /**
     * @test
     */
    public function expectationDoesNotThrowAssertionFailureIfCodeDoesNotThrowAnyException(): void
    {
        expect(function() {
                expect(function() { /* intentionally empty */ })->doesNotThrow();
        })
        ->doesNotThrow();
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationDoesNotThrowAssertionFailureIfCodeDoesNotThrowExpectedExceptionType(\Throwable $throwable): void
    {
        expect(function() use($throwable) {
            expect(function() { /* intentionally empty */ })
                    ->doesNotThrow(get_class($throwable));
        })
        ->doesNotThrow();
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationDoesThrowAssertionFailureWhenCodeThrowsUnexpectedException(\Throwable $throwable): void
    {
        expect(function() use($throwable) {
            expect(function() use($throwable) { throw $throwable; })
                    ->doesNotThrow();
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that no exception is thrown, got '
                . get_class($throwable) . ' with message "not catched".'
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationDoesThrowAssertionFailureWhenCodeThrowsUnexpectedExceptionType(\Throwable $throwable): void
    {
        expect(function() use($throwable) {
            expect(function() use($throwable) { throw $throwable; })
                    ->doesNotThrow(get_class($throwable));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that no exception of type "'
                . get_class($throwable)
                . '" is thrown, got '
                . get_class($throwable)
                . ' with message "not catched".'
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationDoesNotThrowAssertionFailureWhenCodeThrowsOtherExceptionType(\Throwable $throwable, string $other): void
    {
        expect(function() use($throwable, $other) {
            expect(function()  use($throwable) { throw $throwable; })
                    ->doesNotThrow($other);
        })
        ->doesNotThrow();
    }

    /**
     * @test
     */
    public function expectationReturnsItselfWhenResultCheckSucceeds(): void
    {
        $expectation = expect(function() { return true; });
        assertThat($expectation->result(isTrue()), isSameAs($expectation));
    }

    /**
     * @test
     */
    public function expectationDoesNotThrowAssertionFailureWhenResultFulfillsPredicate(): void
    {
        expect(function() {
            expect(function() { return true; })->result(isTrue());
        })
        ->doesNotThrow();
    }

    /**
     * @test
     */
    public function expectationThrowsAssertionFailureWhenResultDoesNotFulfillPredicate(): void
    {
        expect(function() {
            expect(function() { return true; })->result(isFalse());
        })
        ->throws(AssertionFailure::class)
        ->withMessage('Failed asserting that true is false.');
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationThrowsAssertionFailureWhenResultNotAvailableBecauseCodeThrowsException(\Throwable $throwable): void
    {
        expect(function() use($throwable) {
            expect(function() use($throwable) { throw $throwable; })
                    ->result(isTrue());
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that result is true because exception of type '
                . get_class($throwable)
                . ' with message "not catched" was thrown.'
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationThrowsAssertionFailureWhenResultNotAvailableBecauseCodeThrowsExceptionWithCallable(\Throwable $throwable): void
    {
        expect(function() use($throwable) {
            expect(function() use($throwable) { throw $throwable;})
                    ->result('is_int');
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that result satisfies is_int() because exception of type '
                . get_class($throwable) . ' with message "not catched" was thrown.'
        );
    }

    /**
     * @test
     */
    public function expectationCanAssertAfterCodeExecution(): void
    {
        $expectation = expect(function() { return false; });
        assertThat($expectation->after(true, isTrue()), isSameAs($expectation));
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function expectationCanAssertAfterCodeExecutionEvenIfExceptionThrown(\Throwable $throwable): void
    {
        $expectation = expect(function() use($throwable) {
                throw $throwable;
        });
        assertThat($expectation->after(true, isTrue()), isSameAs($expectation));
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function codeIsOnlyExecutedOnce(\Throwable $throwable): void
    {
        $expectation = expect(function() use($throwable) {
                static $count = 0;
                $count++;
                if (1 !== $count) {
                    throw $throwable;
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
    public function expectedExceptionThrowsInvalidArgumentExceptionWhenValueToTestIsNotAnException(): void
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
     * @group  issue_5
     * @since  2.1.0
     */
    public function expectedErrorThrowsInvalidArgumentExceptionWhenValueToTestIsNotCatchedError(): void
    {
        expect(function() {
                $expectedException = new ExpectedError(E_NOTICE);
                $expectedException->test(404);
        })
        ->throws(\InvalidArgumentException::class)
        ->withMessage('Given value is not an error');
    }

    /**
     * @test
     * @dataProvider  throwables
     * @group  issue_1
     * @since  1.6.1
     */
    public function outputOfUnexpectedExceptionTypeIsHelpful(\Throwable $throwable, string $other): void
    {
        expect(function() use ($throwable, $other) {
                expect(function() use ($throwable) { throw $throwable; })
                        ->throws($other);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that exception of type "'
                . get_class($throwable)
                . '" with message "not catched" thrown in ' . __FILE__
                . ' on line ' . $throwable->getLine() . ' matches expected exception "'
                . $other . '".'
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     * @since  2.1.0
     */
    public function outputOfUnexpectedExceptionIsHelpful(\Throwable $throwable, string $other): void
    {
        expect(function() use ($throwable, $other) {
                expect(function() use ($throwable) { throw $throwable; })
                        ->throws(new $other());
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that object of type "'
                . get_class($throwable)
                . '" is identical to object of type "'
                . $other . '".'
        );
    }

    /**
     * @test
     * @group  issue_5
     * @since  2.1.0
     */
    public function outputOfUnexpectedErrorIsHelpful(): void
    {
        $line = null;
        expect(function() use(&$line) {
                expect(function() use(&$line) {
                        $line = __LINE__; trigger_error('error', E_USER_WARNING);
                })
                ->triggers(E_USER_NOTICE);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that error of level "E_USER_WARNING" with '
                . 'message "error" triggered in ' . __FILE__
                . ' on line ' . $line . ' matches expected error "E_USER_NOTICE".'
        );
    }
}
