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
 * Predicate which tests that two other predicates are true.
 */
class AndPredicate extends Predicate
{
    use CombinedPredicate;

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value): bool
    {
        return $this->leftPredicate->test($value) && $this->rightPredicate->test($value);
    }

    /**
     * returns combination operator as string
     *
     * @return  string
     */
    protected function operator(): string
    {
        return 'and';
    }
}
