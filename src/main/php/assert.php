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

/**
 * assert that a value fulfills a predicate
 *
 * @param   mixed                                        $value      value to test
 * @param   \bovigo\assert\predicate\Predicate|callable  $predicate  predicate or callable to test given value
 * @param   string                                       $message    optional  additional description for failure message
 * @return  true
 * @throws  \bovigo\assert\AssertionFailure
 */
function assert($value, $predicate, $message = null)
{
    return (new Assertion($value, exporter()))
            ->compliesTo(Predicate::castFrom($predicate), $message);
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
if (class_exists('PHPUnit_Util_Blacklist')) {
    \PHPUnit_Util_Blacklist::$blacklistedClassNames = array_merge(
            \PHPUnit_Util_Blacklist::$blacklistedClassNames,
            ['bovigo\assert\Assertion' => 1]
    );
}
