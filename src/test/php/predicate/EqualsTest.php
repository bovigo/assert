<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use bovigo\assert\AssertionFailure;
use DateTime;
use PHPUnit\Framework\TestCase;
use TypeError;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\Equals.
 *
 * @group  predicate
 */
class EqualsTest extends TestCase
{
    /**
     * @return  array<array<mixed>>
     */
    public function tuplesEvaluatingToTrue(): array
    {
        return [[true, true],
                [false, false],
                [5, 5],
                [null, null],
                ['foo', 'foo'],
                [true, 5],
                [false, 0],
                [false, null]
        ];
    }

    /**
     * @param  scalar  $expected
     * @param  mixed   $value
     * @test
     * @dataProvider  tuplesEvaluatingToTrue
     */
    public function evaluatesToTrue(mixed $expected, mixed $value): void
    {
        assertTrue(equals($expected)->test($value));
    }

    /**
     * @return  array<array<mixed>>
     */
    public function tuplesEvaluatingToFalse(): array
    {
        return [[true, false],
                [false, true],
                [false, new \stdClass()],
                [5, 'foo'],
                [5, 6],
              //  [true, 'foo'], // TODO disabled until further investigation
                ['foo', 'bar'],
                [5, new \stdClass()],
                ['foo', new \stdClass()]
        ];
    }

    /**
     * @param  scalar  $expected
     * @param  mixed   $value
     * @test
     * @dataProvider  tuplesEvaluatingToFalse
     */
    public function evaluatesToFalse(mixed $expected, mixed $value): void
    {
        assertFalse(equals($expected)->test($value));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(fn() => assertThat('bar', equals('foo'), 'additional info'))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that 'bar' is equal to <string:foo>.
--- Expected
+++ Actual
@@ @@
-'foo'
+'bar'

additional info"
            );
    }

    /**
     * @test
     */
    public function assertionFailureDoesNotReferenceStringWithLinebreaksInMessage(): void
    {
        expect(fn() => assertThat('bar', equals("foo\n"), 'additional info'))
            ->throws(AssertionFailure::class)
            ->withMessage(
                "Failed asserting that 'bar' is equal to <text>.
--- Expected
+++ Actual
@@ @@
-'foo\\n
-'
+'bar'

additional info"
            );
    }

    /**
     * @test
     * @since  6.0.0
     */
    public function equalsWithDelta(): void
    {
        assertTrue(equals(5)->withDelta(0.1)->test(4.9));
    }

    /**
     * @test
     * @since  6.0.0
     */
    public function equalsWithFailingDelta(): void
    {
        assertFalse(equals(5)->withDelta(0.1)->test(4.8));
    }

    /**
     * @test
     * @since  6.0.0
     */
    public function notEqualsWithDelta(): void
    {
        assertTrue(isNotEqualTo(5)->withDelta(0.1)->test(4.8));
    }

    /**
     * @test
     * @since  6.0.0
     */
    public function notEqualsWithFailingDelta(): void
    {
        assertFalse(isNotEqualTo(5)->withDelta(0.1)->test(4.9));
    }

    /**
     * @test
     * @since 7.0.1
     * @group delta_initialized_incorrectly
     */
    public function deltaMustNotBeNull(): void
    {
        $d1 = new DateTime('1980-05-28 06:30:00 Europe/Berlin');
        $d2 = new DateTime('@328336200');
        expect(fn() => assertThat($d1, equals($d2)))
            ->doesNotThrow(TypeError::class);
    }
}
