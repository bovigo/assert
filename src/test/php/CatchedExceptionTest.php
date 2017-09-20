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
    isSameAs,
    isNotSameAs
};
/**
 * Tests for bovigo\assert\CatchedException.
 *
 * @since  1.6.0
 */
class CatchedExceptionTest extends TestCase
{
    public static function throwables(): array
    {
        return ['exception' => [new \Exception('failure', 2)],
                'error'     => [new \Error('failure', 2)]
        ];
    }

    private function catchedException(\Throwable $throwable): CatchedException
    {
        return new CatchedException($throwable);
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function withMessageComparesUsingEquals(\Throwable $throwable)
    {
        assert(
                $this->catchedException($throwable)->withMessage('failure'),
                isInstanceOf(CatchedException::class)
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function withMessageFailsThrowsAssertionFailure(\Throwable $throwable)
    {
        expect(function() use($throwable) {
                $this->catchedException($throwable)->withMessage('error');
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
     * @dataProvider  throwables
     */
    public function messageAssertsWithGivenPredicate(\Throwable $throwable)
    {
        assert(
                $this->catchedException($throwable)->message(contains('fail')),
                isInstanceOf(CatchedException::class)
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function messageAssertsWithGivenPredicateThrowsAssertionFailureWhenPredicateFails(\Throwable $throwable)
    {
        expect(function() use($throwable) {
                $this->catchedException($throwable)->message(contains('error'));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that exception message 'failure' contains 'error'."
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function withCodeComparesUsingEquals(\Throwable $throwable)
    {
        assert(
                $this->catchedException($throwable)->withCode(2),
                isInstanceOf(CatchedException::class)
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function withCodeFailsThrowsAssertionFailure(\Throwable $throwable)
    {
        expect(function() use($throwable) {
                $this->catchedException($throwable)->withCode(3);
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                "Failed asserting that exception code 2 is equal to 3."
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function withAppliesPredicateToException(\Throwable $throwable)
    {
        $this->catchedException($throwable)->with(isSameAs($throwable));
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function withReturnsSelfOnSuccess(\Throwable $throwable)
    {
        $catchedException = $this->catchedException($throwable);
        assert(
                $catchedException->with(function() { return true; }),
                isSameAs($catchedException)
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function withThrowsAssertionFailureWhenPredicateFails(\Throwable $throwable)
    {
        expect(function() use($throwable) {
                $this->catchedException($throwable)->with(
                        isNotSameAs($throwable),
                        'additional info'
                );
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that object of type "' . get_class($throwable)
                . '" is not identical to object of type "' . get_class($throwable)
                . '".
additional info'
        );
    }

    /**
     * @test
     * @dataProvider  throwables
     */
    public function afterExecutesGivenPredicateWithGivenValue(\Throwable $throwable)
    {
        $catchedException = $this->catchedException($throwable);
        $catchedException->after(
                $catchedException,
                isSameAs($catchedException)
        );
    }
}
