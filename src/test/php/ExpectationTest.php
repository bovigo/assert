<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;

use BadFunctionCallException;
use bovigo\assert\predicate\ExpectedError;
use bovigo\assert\predicate\ExpectedException;
use Error;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Throwable;
use TypeError;

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
    public static function provideThrowables(): iterable
    {
        yield 'exception against exception' => [
            new Exception('not catched', 2),
            BadFunctionCallException::class
        ];
        yield 'exception against error' => [
            new Exception('not catched', 2),
            TypeError::class
        ];
        yield 'error against error' => [
            new Error('not catched', 2),
            TypeError::class
        ];
        yield 'error against exception' => [
            new Error('not catched', 2),
            BadFunctionCallException::class
        ];
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationReturnsCatchedExceptionWhenThrowsSucceeds(
        Throwable $throwable
    ): void {
        assertThat(
            expect(fn() => throw $throwable)->throws(),
            isInstanceOf(CatchedException::class)
        );
    }

    /**
     * @since  2.1.0
     */
    #[Test]
    #[Group('issue_5')]
    public function expectationReturnsCatchedErrorWhenTriggersSucceeds(): void
    {
        assertThat(
            expect(fn() => trigger_error('error'))->triggers(),
            isInstanceOf(TriggeredError::class)
        );
    }

    /**
     * @since  2.1.0
     */
    #[Test]
    #[Group('issue_5')]
    public function expectationThrowsInvalidArgumentExceptionWhenExpectingUnknownErrorLevel(): void
    {
        expect(fn() => expect(function() { /* doesn't matter */ })->triggers(303))
            ->throws(\InvalidArgumentException::class)
            ->withMessage('Unknown error level 303');
    }

    #[Test]
    public function expectationReturnsItselfWhenDoesNotThrowSucceeds(): void
    {
        $expectation = expect(function() { /* intentionally empty */});
        assertThat($expectation->doesNotThrow(), isSameAs($expectation));
    }

    #[Test]
    public function expectationThrowsAssertionFailureWhenCodeDoesNotThrowAnyExpectedException(): void
    {
        expect(fn() => expect(function() { /* intentionally empty */ })->throws())
            ->throws(AssertionFailure::class)
            ->withMessage('Failed asserting that an exception is thrown.');
    }

    /**
     * @since  2.1.0
     */
    #[Test]
    #[Group('issue_5')]
    public function expectationThrowsAssertionFailureWhenCodeDoesNotTriggerAnyExpectedError(): void
    {
        expect(fn() => expect(function() { /* intentionally empty */ })->triggers())
            ->throws(AssertionFailure::class)
            ->withMessage('Failed asserting that an error is triggered.');
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationThrowsAssertionFailureWhenCodeDoesNotThrowExpectedExceptionType(
        Throwable $throwable
    ): void {
        expect(fn() => expect(function() { /* intentionally empty */ })->throws(get_class($throwable)))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that exception of type "'
                . get_class($throwable) . '" is thrown.'
            );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationThrowsAssertionFailureWhenCodeDoesNotThrowExpectedException(
        Throwable $throwable
    ): void {
        expect(fn() => expect(function() { /* intentionally empty */ })->throws($throwable))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that exception of type "'
                . get_class($throwable) . '" is thrown.'
            );
    }

    /**
     * @since  2.1.0
     */
    #[Test]
    #[Group('issue_5')]
    public function expectationThrowsAssertionFailureWhenCodeDoesNotTriggerExpectedErrorLevel(): void
    {
        expect(fn() => expect(function() { /* intentionally empty */ })->triggers(E_USER_WARNING))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that error of type "E_USER_WARNING" is triggered.'
            );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationDoesNotThrowAssertionFailureWhenCodeThrowsAnyExpectedException(
        Throwable $throwable
    ): void {
        expect(fn() => expect(fn() => throw $throwable)->throws())
            ->doesNotThrow();
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationDoesNotThrowAssertionFailureWhenCodeThrowsExpectedExceptionType(
        Throwable $throwable
    ): void {
        expect(fn() => expect(fn() => throw $throwable)->throws(get_class($throwable)))
            ->doesNotThrow();
    }

    /**
     * @since  2.1.0
     */
    #[Test]
    #[Group('issue_5')]
    public function expectationDoesNotThrowAssertionFailureWhenCodeTriggersAnyExpectedError(): void
    {
        expect(fn() => expect(fn() => trigger_error('error'))->triggers())
            ->doesNotThrow();
    }

    /**
     * @since  2.1.0
     */
    #[Test]
    #[Group('issue_5')]
    public function expectationDoesNotThrowAssertionFailureWhenCodeTriggersExpectedErrorLevel(): void
    {
        expect(fn() => expect(fn() => trigger_error('error', E_USER_WARNING))->triggers(E_USER_WARNING))
            ->doesNotThrow();
    }

    #[Test]
    public function expectationDoesNotThrowAssertionFailureIfCodeDoesNotThrowAnyException(): void
    {
        expect(fn() =>  expect(function() { /* intentionally empty */ })->doesNotThrow())
            ->doesNotThrow();
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationDoesNotThrowAssertionFailureIfCodeDoesNotThrowExpectedExceptionType(
        Throwable $throwable
    ): void {
        expect(fn() => expect(
            function() { /* intentionally empty */ })->doesNotThrow(get_class($throwable)
        ))
            ->doesNotThrow();
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationDoesThrowAssertionFailureWhenCodeThrowsUnexpectedException(
        Throwable $throwable
    ): void {
        expect(fn() => expect(fn() => throw $throwable)->doesNotThrow())
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that no exception is thrown, got '
                . get_class($throwable) . ' with message "not catched".'
            );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationDoesThrowAssertionFailureWhenCodeThrowsUnexpectedExceptionType(
        Throwable $throwable
    ): void {
        expect(fn() => expect(fn() => throw $throwable)->doesNotThrow(get_class($throwable)))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that no exception of type "'
                . get_class($throwable)
                . '" is thrown, got '
                . get_class($throwable)
                . ' with message "not catched".'
            );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationDoesNotThrowAssertionFailureWhenCodeThrowsOtherExceptionType(
        Throwable $throwable, string $other
    ): void {
        expect(fn() => expect(fn() => throw $throwable)->doesNotThrow($other))
            ->doesNotThrow();
    }

    #[Test]
    public function expectationReturnsItselfWhenResultCheckSucceeds(): void
    {
        $expectation = expect(fn() => true);
        assertThat($expectation->result(isTrue()), isSameAs($expectation));
    }

    #[Test]
    public function expectationDoesNotThrowAssertionFailureWhenResultFulfillsPredicate(): void
    {
        expect(fn() => expect(fn() => true)->result(isTrue()))
            ->doesNotThrow();
    }

    #[Test]
    public function expectationThrowsAssertionFailureWhenResultDoesNotFulfillPredicate(): void
    {
        expect(fn() => expect(fn() => true)->result(isFalse()))
            ->throws(AssertionFailure::class)
            ->withMessage('Failed asserting that true is false.');
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationThrowsAssertionFailureWhenResultNotAvailableBecauseCodeThrowsException(
        Throwable $throwable
    ): void {
        expect(fn() => expect(fn() => throw $throwable)->result(isTrue()))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that result is true because exception of type '
                . get_class($throwable)
                . ' with message "not catched" was thrown.'
            );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationThrowsAssertionFailureWhenResultNotAvailableBecauseCodeThrowsExceptionWithCallable(
        Throwable $throwable
    ): void {
        expect(fn() => expect(fn() => throw $throwable)->result('is_int'))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that result satisfies is_int() because exception of type '
                . get_class($throwable) . ' with message "not catched" was thrown.'
            );
    }

    #[Test]
    public function expectationCanAssertAfterCodeExecution(): void
    {
        $expectation = expect(fn() => false);
        assertThat($expectation->after(true, isTrue()), isSameAs($expectation));
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function expectationCanAssertAfterCodeExecutionEvenIfExceptionThrown(Throwable $throwable): void
    {
        $expectation = expect(fn() => throw $throwable);
        assertThat($expectation->after(true, isTrue()), isSameAs($expectation));
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function codeIsOnlyExecutedOnce(Throwable $throwable): void
    {
        $expectation = expect(function() use($throwable) {
                static $count = 0;
                $count++;
                if (1 !== $count) {
                    throw $throwable;
                }

                return true;
        });

        expect(fn() => $expectation->result(isTrue())->result(isTrue()))
            ->doesNotThrow();
    }

    #[Test]
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
     * @since  2.1.0
     */
    #[Test]
    #[Group('issue_5')]
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
     * @since  1.6.1
     */
    #[Test]
    #[DataProvider('provideThrowables')]
    #[Group('issue_1')]
    public function outputOfUnexpectedExceptionTypeIsHelpful(Throwable $throwable, string $other): void
    {
        expect(fn() => expect(fn() => throw $throwable)->throws($other))
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
     * @since  2.1.0
     */
    #[Test]
    #[DataProvider('provideThrowables')]
    public function outputOfUnexpectedExceptionIsHelpful(Throwable $throwable, string $other): void
    {
        expect(fn() => expect(fn() => throw $throwable)->throws(new $other()))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that object of type "'
                . get_class($throwable)
                . '" is identical to object of type "'
                . $other . '".'
            );
    }

    /**
     * @since  2.1.0
     */
    #[Test]
    #[Group('issue_5')]
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
