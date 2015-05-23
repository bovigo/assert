<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Negates evaluation of wrapped predicate.
 */
class NegatePredicate extends Predicate
{
    /**
     * @type  Predicate
     */
    private $predicate;

    /**
     * constructor
     *
     * @param  \bovigo\assert\predicate\Predicate|callable  $predicate
     */
    public function __construct($predicate)
    {
        $this->predicate = Predicate::castFrom($predicate);
    }

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value)
    {
        return !$this->predicate->test($value);
    }
}
