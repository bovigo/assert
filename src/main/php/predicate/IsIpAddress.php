<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to test that something is an IP address, either v4 or v6.
 */
class IsIpAddress extends Predicate
{
    use ReusablePredicate;

    /**
     * test that the given value is an IP address (either v4 or v6)
     *
     * @param   mixed  $value
     * @return  bool   true if value is equal to expected value, else false
     */
    public function test($value)
    {
        if (IsIpV4Address::instance()->test($value)) {
            return true;
        }

        return IsIpV6Address::instance()->test($value);
    }
}
