<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Common instance creation for predicates combining two other predicates.
 */
trait CombinedPredicate
{
    /**
     * @type  Predicate
     */
    private $leftPredicate;
    /**
     * @type  Predicate
     */
    private $rightPredicate;

    /**
     * constructor
     *
     * @param  \bovigo\assert\predicate\Predicate|callable  $predicate1
     * @param  \bovigo\assert\predicate\Predicate|callable  $predicate2
     */
    public function __construct($predicate1, $predicate2)
    {
        $this->leftPredicate  = Predicate::castFrom($predicate1);
        $this->rightPredicate = Predicate::castFrom($predicate2);
    }

    /**
     * returns amount of checks done in this predicate
     *
     * @return  int
     */
    public function count()
    {
        return count($this->leftPredicate) + count($this->rightPredicate);
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return '(' . $this->leftPredicate
                . ' ' . $this->operator() . ' '
                . $this->rightPredicate . ')';
    }

    /**
     * returns combination operator as string
     *
     * @return  string
     */
    protected abstract function operator();
}
