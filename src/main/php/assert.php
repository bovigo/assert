<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use bovigo\assert\predicate\Predicate;
use SebastianBergmann\Exporter\Exporter;

use function bovigo\assert\predicate\equals;
use function bovigo\assert\predicate\isFalse;
use function bovigo\assert\predicate\isEmpty;
use function bovigo\assert\predicate\isNotEmpty;
use function bovigo\assert\predicate\isNotNull;
use function bovigo\assert\predicate\isNull;
use function bovigo\assert\predicate\isTrue;

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
function assert($value, $predicate, $description = null)
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
function fail($description)
{
    throw new AssertionFailure($description);
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
function assertTrue($value, $description = null)
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
function assertFalse($value, $description = null)
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
function assertNull($value, $description = null)
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
function assertNotNull($value, $description = null)
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
function assertEmpty($value, $description = null)
{
    return assert($value, isEmpty(), $description);
}

/**
 * alias for assert($value, equals(''), $description)
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  1.5.0
 */
function assertEmpyString($value, $description = null)
{
    return assert($value, equals(''), $description);
}

/**
 * alias for assert($value, equals([]), $description)
 * @api
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  1.5.0
 */
function assertEmpyArray($value, $description = null)
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
function assertNotEmpty($value, $description = null)
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
function counting(Predicate $predicate)
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
function export($value)
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
function exporter()
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
