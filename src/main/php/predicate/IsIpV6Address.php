<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to test that something is an IPv6 address.
 */
class IsIpV6Address extends Predicate
{
    use ReusablePredicate;

    /**
     * test that the given value is an IPv6 address
     *
     * @param   mixed  $value
     * @return  bool   true if value is equal to expected value, else false
     */
    public function test($value)
    {
        $hexquads = explode(':', $value);
        // Shortest address is ::1, this results in 3 parts
        if (sizeof($hexquads) < 3) {
            return false;
        }

        if ('' == $hexquads[0]) {
            array_shift($hexquads);
        }

        foreach ($hexquads as $hq) {
            // Catch cases like ::ffaadd00::
            if (strlen($hq) > 4) {
                return false;
            }

            // Not hex
            if (strspn($hq, '0123456789abcdefABCDEF') < strlen($hq)) {
                return false;
            }
        }

        return true;
    }
}
