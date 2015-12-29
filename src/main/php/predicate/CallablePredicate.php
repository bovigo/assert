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
     * @type  string
     */
    private $description = null;

    /**
     * constructor
     *
     * The description will be used instead of the default description in the
     * string representation of this predicate.
     *
     * @param  callable  $predicate
     * @param  string    $description  optional  description for predicate
     */
    public function __construct(callable $predicate, $description = null)
    {
        $this->predicate   = $predicate;
        $this->description = $description;
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
        if (empty($this->description)) {
            return 'satisfies ' . $this->predicateName();
        }

        return $this->description;
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

        return 'a lambda function';
    }
}
