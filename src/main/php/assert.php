<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use SebastianBergmann\Exporter\Exporter;
/**
 * creates assertion for given value
 *
 * @param   mixed  $value
 * @return  \bovigo\assert\Assertion
 */
function that($value)
{
    static $exporter = null;
    if (null === $exporter) {
        $exporter = new Exporter();
    }

    return new Assertion($value, $exporter);
}

// blacklist our own classes from being displayed in PHPUnit error stacks
if (class_exists('PHPUnit_Util_Blacklist')) {
    \PHPUnit_Util_Blacklist::$blacklistedClassNames = array_merge(
            \PHPUnit_Util_Blacklist::$blacklistedClassNames,
            ['bovigo\assert\Assertion' => 1]
    );
}
