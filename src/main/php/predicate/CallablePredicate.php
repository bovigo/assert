<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Wraps a predicate evaluation into a callable.
 */
class CallablePredicate extends Predicate
{
    /**
     * @type  callable
     */
    private $predicate;

    /**
     * constructor
     *
     * @param  callable  $predicate
     */
    public function __construct(callable $predicate)
    {
        $this->predicate = $predicate;
    }

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value)
    {
        $predicate = $this->predicate;
        return $predicate($value);
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return sprintf('callable<%s>', $this->predicateName());
    }

    /**
     * tries to determine a name for the callable
     *
     * @return  string
     */
    private function predicateName()
    {
        if (is_array($this->predicate)) {
            if (is_string($this->predicate[0])) {
                return $this->predicate[0] . '::' . $this->predicate[1] . '()';
            }

            return get_class($this->predicate[0]) . '->' . $this->predicate[1] . '()';
        } elseif (is_string($this->predicate)) {
            return $this->predicate . '()';
        }

        return 'lambda';
    }
}
