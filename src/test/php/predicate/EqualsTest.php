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
use PHPUnit\Framework\TestCase;

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
    public function evaluatesToTrue($expected, $value): void
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
                [true, 'foo'],
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
    public function evaluatesToFalse($expected, $value): void
    {
        assertFalse(equals($expected)->test($value));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(function() { assertThat('bar', equals('foo'), 'additional info'); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 'bar' is equal to <string:foo>."
                        . PHP_EOL
                        . '--- Expected'
                        . PHP_EOL
                        . '+++ Actual'
                        . PHP_EOL
                        . '@@ @@'
                        . PHP_EOL
                        . "-'foo'"
                        . PHP_EOL
                        . "+'bar'"
                        . PHP_EOL
                        . PHP_EOL
                        . 'additional info'
        );
    }

    /**
     * @test
     */
    public function assertionFailureDoesNotReferenceStringWithLinebreaksInMessage(): void
    {
        expect(function() { assertThat('bar', equals("foo\n"), 'additional info'); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 'bar' is equal to <text>."
                        . PHP_EOL
                        . '--- Expected'
                        . PHP_EOL
                        . '+++ Actual'
                        . PHP_EOL
                        . '@@ @@'
                        . PHP_EOL
                        . "-'foo\\n"
                        . PHP_EOL
                        . "-'"
                        . PHP_EOL
                        . "+'bar'"
                        . PHP_EOL
                        . PHP_EOL
                        . 'additional info'
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
}
