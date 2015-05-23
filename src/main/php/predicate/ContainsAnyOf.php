<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to test that something contains any of the allowed values.
 */
class ContainsAnyOf extends Predicate
{
    /**
     * the scalar value to be contained in value to validate
     *
     * @type  string[]
     */
    private $contained;

    /**
     * constructor
     *
     * @param  string[]  $contained
     */
    public function __construct(array $contained)
    {
        $this->contained = $contained;
    }

    /**
     * tests that the given value contains any of the allowed values
     *
     * @param   scalar|null  $value
     * @return  bool
     */
    public function test($value)
    {
        if (!is_scalar($value) || null === $value) {
            return false;
        }

        foreach ($this->contained as $contained) {
            if (is_bool($contained)) {
                if ($value === $contained) {
                    return true;
                }

                continue;
            }

            if ($value === $contained || false !== strpos($value, (string) $contained)) {
                return true;
            }
        }

        return false;
    }
}
