<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Error;
use Exception;
use Throwable;

use function bovigo\assert\predicate\{
    contains,
    isInstanceOf,
    isSameAs,
    isNotSameAs
};
/**
 * Tests for bovigo\assert\CaughtThrowable.
 *
 * @since  1.6.0
 */
class CaughtThrowableTest extends TestCase
{
    public static function provideThrowables(): iterable
    {
        $exception = new Exception('failure', 2);
        yield 'exception' => [new CaughtThrowable($exception), $exception];
        $error = new Error('failure', 2);
        yield 'error' => [new CaughtThrowable($error), $error];
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function withMessageComparesUsingEquals(CaughtThrowable $caughtThrowable): void
    {
        assertThat(
            $caughtThrowable->withMessage('failure'),
            isInstanceOf(CaughtThrowable::class)
        );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function withMessageFailsThrowsAssertionFailure(CaughtThrowable $caughtThrowable): void
    {
        expect(fn() => $caughtThrowable->withMessage('error'))
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

    #[Test]
    #[DataProvider('provideThrowables')]
    public function messageAssertsWithGivenPredicate(CaughtThrowable $caughtThrowable): void
    {
        assertThat(
            $caughtThrowable->message(contains('fail')),
            isInstanceOf(CaughtThrowable::class)
        );
    }

    /**
     * @since 5.0.1
     */
    #[Test]
    #[DataProvider('provideThrowables')]
    public function messageAssertsWithGivenCallable(CaughtThrowable $caughtThrowable): void
    {
        assertThat(
            $caughtThrowable->message('is_string'),
            isInstanceOf(CaughtThrowable::class)
        );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function messageAssertsWithGivenPredicateThrowsAssertionFailureWhenPredicateFails(
        CaughtThrowable $caughtThrowable
    ): void {
        expect(fn() => $caughtThrowable->message(contains('error')))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that exception message 'failure' contains 'error'."
            );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function withCodeComparesUsingEquals(CaughtThrowable $caughtThrowable): void
    {
        assertThat(
            $caughtThrowable->withCode(2),
            isInstanceOf(CaughtThrowable::class)
        );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function withCodeFailsThrowsAssertionFailure(CaughtThrowable $caughtThrowable): void
    {
        expect(fn() => $caughtThrowable->withCode(3))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that exception code 2 is equal to 3."
            );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function withAppliesPredicateToException(
        CaughtThrowable $caughtThrowable,
        Throwable $throwable
    ): void {
        $caughtThrowable->with(isSameAs($throwable));
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function withReturnsSelfOnSuccess(CaughtThrowable $caughtThrowable): void
    {
        assertThat(
            $caughtThrowable->with(fn() => true),
            isSameAs($caughtThrowable)
        );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function withThrowsAssertionFailureWhenPredicateFails(
        CaughtThrowable $caughtThrowable,
        Throwable $throwable
    ): void {
        expect(fn() => $caughtThrowable->with(
            isNotSameAs($throwable),
            'additional info'
        ))
            ->throws(AssertionFailure::class)
            ->withMessage(
                'Failed asserting that object of type "' . get_class($throwable)
                . '" is not identical to object of type "' . get_class($throwable)
                . '".
additional info'
        );
    }

    #[Test]
    #[DataProvider('provideThrowables')]
    public function afterExecutesGivenPredicateWithGivenValue(CaughtThrowable $caughtThrowable): void
    {
        $caughtThrowable->after($caughtThrowable, isSameAs($caughtThrowable));
    }
}
