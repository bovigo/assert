<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to test that a string is a mail address.
 */
class IsMailAddress extends Predicate
{
    use ReusablePredicate;

    /**
     * test that the given value is an e-mail address
     *
     * @param   string  $value
     * @return  bool    true if value is an e-mail address, else false
     */
    public function test($value)
    {
        if (null == $value || strlen($value) == 0) {
            return false;
        }

        $url = @parse_url('mailto://' . $value);
        if (!isset($url['host']) || !preg_match('/^([a-zA-Z0-9-]*)\.([a-zA-Z]{2,4})$/', $url['host'])) {
            return false;
        }

        if (!isset($url['user']) || strlen($url['user']) == 0 || !preg_match('/^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*$/', $url['user'])) {
            return false;
        }

        return true;
    }
}
