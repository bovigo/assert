<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;

use bovigo\assert\predicate\Predicate;
use SebastianBergmann\Exporter\Exporter;

use function bovigo\assert\predicate\{
    equals,
    isFalse,
    isEmpty,
    isNotEmpty,
    isNotNull,
    isNull,
    isTrue
};

/**
 * assert that a value fulfills a predicate
 *
 * @api
 * @since  3.1.0
 * @throws AssertionFailure
 */
function assertThat(mixed $value, Predicate|callable $predicate, string $description = null): bool
{
    return (new Assertion($value, exporter()))
            ->evaluate(counting(Predicate::castFrom($predicate)), $description);
}

/**
 * fail a test with given message
 *
 * @api
 * @throws AssertionFailure
 * @since  1.2.0
 */
function fail(string $description): never
{
    throw new AssertionFailure($description);
}

/**
 * sets up an expectation for given code
 *
 * @since 1.6.0
 */
function expect(callable $code): Expectation
{
    return new Expectation($code);
}

/**
 * asserts that the output of given code satisfies given predicate
 *
 * @throws AssertionFailure
 * @since  2.1.0
 */
function outputOf(callable $code, Predicate|callable $predicate, string $description = null): bool
{
    ob_start();
    $code();
    $printed = ob_get_contents();
    ob_end_clean();
    return assertThat($printed, $predicate, $description);
}

/**
 * alias for assertThat($value, isTrue()[, $description])
 *
 * @api
 * @since 1.3.0
 */
function assertTrue(mixed $value, string $description = null): bool
{
    return assertThat($value, isTrue(), $description);
}

/**
 * alias for assertThat($value, isFalse()[, $description])
 *
 * @api
 * @since 1.3.0
 */
function assertFalse(mixed $value, string $description = null): bool
{
    return assertThat($value, isFalse(), $description);
}

/**
 * alias for assertThat($value, isNull()[, $description])
 *
 * @api
 * @since 1.3.0
 */
function assertNull(mixed $value, string $description = null): bool
{
    return assertThat($value, isNull(), $description);
}

/**
 * alias for assertThat($value, isNotNull()[, $description])
 *
 * @api
 * @since 1.3.0
 */
function assertNotNull(mixed $value, string $description = null): bool
{
    return assertThat($value, isNotNull(), $description);
}

/**
 * alias for assertThat($value, isEmpty()[, $description])
 *
 * @api
 * @since 1.3.0
 */
function assertEmpty(mixed $value, string $description = null): bool
{
    return assertThat($value, isEmpty(), $description);
}

/**
 * alias for assertThat($value, equals(''), $description)
 * @api
 * @since 1.5.0
 */
function assertEmptyString(mixed $value, string $description = null): bool
{
    return assertThat($value, equals(''), $description);
}

/**
 * alias for assertThat($value, equals([]), $description)
 *
 * @api
 * @since 1.5.0
 */
function assertEmptyArray(mixed $value, string $description = null): bool
{
    return assertThat($value, equals([]), $description);
}

/**
 * alias for assertThat($value, isNotEmpty()[, $description])
 *
 * @api
 * @since 1.3.0
 */
function assertNotEmpty(mixed $value, string $description = null): bool
{
    return assertThat($value, isNotEmpty(), $description);
}

/**
 * adds predicate count as constraint count to PHPUnit if present
 *
 * This is definitely a hack and might break with future PHPUnit releases.
 *
 * @internal
 * @staticvar \ReflectionProperty $property
 */
function increaseAssertionCounter(int $assertions): void
{
    static $property = null;
    if (null === $property && class_exists('\PHPUnit\Framework\Assert')) {
        $assertClass = new \ReflectionClass(\PHPUnit\Framework\Assert::class);
        $property = $assertClass->getProperty('count');
        $property->setAccessible(true);
    }

    if (null !== $property) {
        $property->setValue(null, $property->getValue() + $assertions);
    }
}

/**
 * adds predicate count as constraint count to PHPUnit if present
 *
 * @internal
 */
function counting(Predicate $predicate): Predicate
{
    increaseAssertionCounter(count($predicate));
    return $predicate;
}

/**
 * exports a value as a string
 *
 * @api
 */
function export(mixed $value): string
{
    return exporter()->export($value);
}

/**
 * returns always the same exporter instance
 *
 * @internal
 * @staticvar Exporter $exporter
 */
function exporter(): Exporter
{
    static $exporter = null;
    if (null === $exporter) {
        $exporter = new Exporter();
    }

    return $exporter;
}

/**
 * exclude our own classes from being displayed in PHPUnit error stacks
 */
if (class_exists(\PHPUnit\Util\ExcludeList::class)) {
    \PHPUnit\Util\ExcludeList::addDirectory(__DIR__);
}
