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
 * @param   mixed                                        $value        value to test
 * @param   \bovigo\assert\predicate\Predicate|callable  $predicate    predicate or callable to test given value
 * @param   string                                       $description  optional  additional description for failure message
 * @return  true
 * @throws  \bovigo\assert\AssertionFailure
 */
function assert($value, callable $predicate, string $description = null): bool
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
function fail(string $description)
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
 * @return  true
 * @throws  \bovigo\assert\AssertionFailure
 * @since   2.1.0
 */
function outputOf(callable $code, callable $predicate, string $description = null): bool
{
    ob_start();
    $code();
    $printed = ob_get_contents();
    ob_end_clean();
    return assert($printed, $predicate, $description);
}

/**
 * alias for assert($value, isTrue()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertTrue($value, string $description = null): bool
{
    return assert($value, isTrue(), $description);
}

/**
 * alias for assert($value, isFalse()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertFalse($value, string $description = null): bool
{
    return assert($value, isFalse(), $description);
}

/**
 * alias for assert($value, isNull()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertNull($value, string $description = null): bool
{
    return assert($value, isNull(), $description);
}

/**
 * alias for assert($value, isNotNull()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertNotNull($value, string $description = null): bool
{
    return assert($value, isNotNull(), $description);
}

/**
 * alias for assert($value, isEmpty()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertEmpty($value, string $description = null): bool
{
    return assert($value, isEmpty(), $description);
}

/**
 * alias for assert($value, equals(''), $description)
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.5.0
 */
function assertEmptyString($value, string $description = null): bool
{
    return assert($value, equals(''), $description);
}

/**
 * alias for assert($value, equals([]), $description)
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.5.0
 */
function assertEmptyArray($value, string $description = null): bool
{
    return assert($value, equals([]), $description);
}

/**
 * alias for assert($value, isNotEmpty()[, $description])
 *
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 * @since   1.3.0
 */
function assertNotEmpty($value, string $description = null): bool
{
    return assert($value, isNotEmpty(), $description);
}

/**
 * adds predicate count as constraint count to PHPUnit if present
 *
 * This is definitely a hack and might break with future PHPUnit releases.
 *
 * @internal
 * @staticvar  \ReflectionProperty  $property
 * @param   \bovigo\assert\predicate\Predicate  $predicate
 * @return  \bovigo\assert\predicate\Predicate
 */
function counting(Predicate $predicate): Predicate
{
    static $property = null;
    if (null === $property && class_exists('\PHPUnit_Framework_Assert')) {
        $assertClass = new \ReflectionClass(\PHPUnit_Framework_Assert::class);
        $property = $assertClass->getProperty('count');
        $property->setAccessible(true);
    }

    if (null !== $property) {
        $property->setValue(null, $property->getValue() + count($predicate));
    }

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
if (class_exists('\PHPUnit_Util_Blacklist')) {
    \PHPUnit_Util_Blacklist::$blacklistedClassNames = array_merge(
            \PHPUnit_Util_Blacklist::$blacklistedClassNames,
            [Assertion::class => 1]
    );
}
