<?php
/**
 * This file is part of bovigo\callmap.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
if (class_exists('\PHPUnit\Framework\ExpectationFailedException')) {
    /**
     * Thrown when amount of received arguments is lower than expected amount.
     */
    class AssertionFailure extends \PHPUnit\Framework\ExpectationFailedException
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
