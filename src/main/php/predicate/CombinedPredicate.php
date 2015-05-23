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
    private $predicate1;
    /**
     * @type  Predicate
     */
    private $predicate2;

    /**
     * constructor
     *
     * @param  \bovigo\assert\predicate\Predicate|callable  $predicate1
     * @param  \bovigo\assert\predicate\Predicate|callable  $predicate2
     */
    public function __construct($predicate1, $predicate2)
    {
        $this->predicate1 = Predicate::castFrom($predicate1);
        $this->predicate2 = Predicate::castFrom($predicate2);
    }
}
