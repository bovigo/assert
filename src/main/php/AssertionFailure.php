<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
if (class_exists('\PHPUnit\Framework\AssertionFailedError')) {
    /**
     * Thrown when amount of received arguments is lower than expected amount.
     */
    class AssertionFailure extends \PHPUnit\Framework\AssertionFailedError
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
