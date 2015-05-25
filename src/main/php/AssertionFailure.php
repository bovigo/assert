<?php
/**
 * This file is part of bovigo\callmap.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
if (class_exists('PHPUnit_Framework_ExpectationFailedException')) {
    /**
     * Thrown when amount of received arguments is lower than expected amount.
     */
    class AssertionFailure extends \PHPUnit_Framework_ExpectationFailedException
    {
        // intentionally empty
    }
} else {
    /**
     * @ignore
     */
    class AssertionFailure extends \Exception
    {
        // intentionally empty
    }
}

