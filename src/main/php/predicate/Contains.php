<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to test that something is contained.
 */
class Contains extends Predicate
{
    /**
     * the scalar value to be contained in value to validate
     *
     * @type  string
     */
    private $contained = null;

    /**
     * constructor
     *
     * @param   scalar|null  $contained
     * @throws  \InvalidArgumentException
     */
    public function __construct($contained)
    {
        if (!is_scalar($contained)) {
            throw new \InvalidArgumentException('Can only check scalar values.');
        }

        $this->contained = $contained;
    }

    /**
     * tests that the given value contains expected value
     *
     * @param   scalar|null  $value
     * @return  bool
     */
    public function test($value)
    {
        if (!is_scalar($value) || null === $value) {
            return false;
        }

        if (is_bool($this->contained)) {
            return ($value === $this->contained);
        }

        if ($value === $this->contained || false !== strpos($value, (string) $this->contained)) {
            return true;
        }

        return false;
    }
}
