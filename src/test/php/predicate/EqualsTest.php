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
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;

use function bovigo\assert\assertFalse;
use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\Equals.
 */
#[Group('predicate')]
class EqualsTest extends TestCase
{
    public static function tuplesEvaluatingToTrue(): Generator
    {
        yield [true, true];
        yield [false, false];
        yield [5, 5];
        yield [null, null];
        yield ['foo', 'foo'];
        yield [true, 5];
        yield [false, 0];
        yield [false, null];
    }

    #[Test]
    #[DataProvider('tuplesEvaluatingToTrue')]
    public function evaluatesToTrue(mixed $expected, mixed $value): void
    {
        assertTrue(equals($expected)->test($value));
    }

    public static function tuplesEvaluatingToFalse(): Generator
    {
        yield [true, false];
        yield [false, true];
        yield [false, new stdClass()];
        yield [5, 'foo'];
        yield [5, 6];
        //yield [true, 'foo']; // TODO disabled until further investigation
        yield ['foo', 'bar'];
        yield [5, new stdClass()];
        yield ['foo', new stdClass()];
    }

    #[Test]
    #[DataProvider('tuplesEvaluatingToFalse')]
    public function evaluatesToFalse(mixed $expected, mixed $value): void
    {
        assertFalse(equals($expected)->test($value));
    }

    #[Test]
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

    #[Test]
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
     * @since 6.0.0
     */
    #[Test]
    public function equalsWithDelta(): void
    {
        assertTrue(equals(5)->withDelta(0.1)->test(4.9));
    }

    /**
     * @since 6.0.0
     */
    #[Test]
    public function equalsWithFailingDelta(): void
    {
        assertFalse(equals(5)->withDelta(0.1)->test(4.8));
    }

    /**
     * @since 6.0.0
     */
    #[Test]
    public function notEqualsWithDelta(): void
    {
        assertTrue(isNotEqualTo(5)->withDelta(0.1)->test(4.8));
    }

    /**
     * @since 6.0.0
     */
    #[Test]
    public function notEqualsWithFailingDelta(): void
    {
        assertFalse(isNotEqualTo(5)->withDelta(0.1)->test(4.9));
    }

    /**
     * @since 7.0.1
     */
    #[Test]
    #[Group('delta_initialized_incorrectly')]
    public function deltaMustNotBeNull(): void
    {
        $d1 = new DateTime('1980-05-28 06:30:00 Europe/Berlin');
        $d2 = new DateTime('@328336200');
        expect(fn() => assertThat($d1, equals($d2)))
            ->doesNotThrow(TypeError::class);
    }
}
