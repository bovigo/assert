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
 * @since   3.1.0
 * @param   mixed                                        $value        value to test
 * @param   \bovigo\assert\predicate\Predicate|callable  $predicate    predicate or callable to test given value
 * @param   string                                       $description  optional  additional description for failure message
 * @return  bool
 * @throws  \bovigo\assert\AssertionFailure
 */
function assertThat($value, callable $predicate, string $description = null): bool
{
    return (new Assertion($value, exporter()))
            ->evaluate(counting(Predicate::castFrom($predicate)), $description);
}

/**
 * fail a test with given message
 *
 * @api
 * @param   string  $description
 * @throws  \bovigo\assert\AssertionFailure
 * @since   1.2.0
 */
function fail(string $description): void
{
    throw new AssertionFailure($description);
}

/**
 * sets up an expectation for given code
 *
 * @param   callable  $code
 * @return  \bovigo\assert\Expectation
 * @since   1.6.0
 */
function expect(callable $code): Expectation
{
    return new Expectation($code);
}

/**
 * asserts that the output of given code satisfies given predicate
 *
 * @param   callable                                     $code
 * @param   \bovigo\assert\predicate\Predicate|callable  $predicate    predicate or callable to test given value
 * @param   string                                       $description  optional  additional description for failure message
 * @return  bool
 * @throws  \bovigo\assert\AssertionFailure
 * @since   2.1.0
 */
function outputOf(callable $code, callable $predicate, string $description = null): bool
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
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertTrue($value, string $description = null): bool
{
    return assertThat($value, isTrue(), $description);
}

/**
 * alias for assertThat($value, isFalse()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertFalse($value, string $description = null): bool
{
    return assertThat($value, isFalse(), $description);
}

/**
 * alias for assertThat($value, isNull()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertNull($value, string $description = null): bool
{
    return assertThat($value, isNull(), $description);
}

/**
 * alias for assertThat($value, isNotNull()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertNotNull($value, string $description = null): bool
{
    return assertThat($value, isNotNull(), $description);
}

/**
 * alias for assertThat($value, isEmpty()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertEmpty($value, string $description = null): bool
{
    return assertThat($value, isEmpty(), $description);
}

/**
 * alias for assertThat($value, equals(''), $description)
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.5.0
 */
function assertEmptyString($value, string $description = null): bool
{
    return assertThat($value, equals(''), $description);
}

/**
 * alias for assertThat($value, equals([]), $description)
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.5.0
 */
function assertEmptyArray($value, string $description = null): bool
{
    return assertThat($value, equals([]), $description);
}

/**
 * alias for assertThat($value, isNotEmpty()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertNotEmpty($value, string $description = null): bool
{
    return assertThat($value, isNotEmpty(), $description);
}

/**
 * adds predicate count as constraint count to PHPUnit if present
 *
 * This is definitely a hack and might break with future PHPUnit releases.
 *
 * @internal
 * @staticvar  \ReflectionProperty  $property
 * @param   int  $assertions
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
 * @param   \bovigo\assert\predicate\Predicate  $predicate
 * @return  \bovigo\assert\predicate\Predicate
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
 * @param   mixed   $value
 * @return  string
 */
function export($value): string
{
    return exporter()->export($value);
}

/**
 * returns always the same exporter instance
 *
 * @internal
 * @staticvar  \SebastianBergmann\Exporter\Exporter  $exporter
 * @return     \SebastianBergmann\Exporter\Exporter
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
 * blacklist our own classes from being displayed in PHPUnit error stacks
 */
if (class_exists(\PHPUnit\Util\Blacklist::class)) {
    if (property_exists(\PHPUnit\Util\Blacklist::class, '$blacklistedClassNames')) {
        \PHPUnit\Util\Blacklist::$blacklistedClassNames = array_merge(
            \PHPUnit\Util\Blacklist::$blacklistedClassNames,
            [Assertion::class => 1]
        );
    } else {
        \PHPUnit\Util\Blacklist::addDirectory(__DIR__);
    }
}
