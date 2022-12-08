<?php
declare(strict_types=1);
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
    private Predicate $leftPredicate;
    private Predicate $rightPredicate;

    public function __construct(Predicate|callable $predicate1, Predicate|callable $predicate2)
    {
        $this->leftPredicate  = Predicate::castFrom($predicate1);
        $this->rightPredicate = Predicate::castFrom($predicate2);
    }

    /**
     * returns amount of checks done in this predicate
     */
    public function count(): int
    {
        return count($this->leftPredicate) + count($this->rightPredicate);
    }

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return $this->leftPredicate
                . ' ' . $this->operator() . ' '
                . $this->rightPredicate;
    }

    /**
     * returns combination operator as string
     */
    protected abstract function operator(): string;
}
