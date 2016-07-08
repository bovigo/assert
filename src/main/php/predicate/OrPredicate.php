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
class OrPredicate extends Predicate
{
    use CombinedPredicate;

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     * @throws  \Exception  in case one of the combined predicates throws an exception
     */
    public function test($value): bool
    {
        $leftE = null;
        try {
            if ($this->leftPredicate->test($value)) {
                return true;
            }
        } catch (\Exception $ex) {
            $leftE = $ex;
        }

        try {
            if ($this->rightPredicate->test($value)) {
                return true;
            }
        } catch (\Exception $rightE) {
            if (null !== $leftE) {
                throw new \Exception(
                        $leftE->getMessage() . "\n" . $rightE->getMessage()
                );
            }

            throw $rightE;
        }

        if (null !== $leftE) {
            throw $leftE;
        }

        return false;
    }

    /**
     * returns combination operator as string
     *
     * @return  string
     */
    protected function operator(): string
    {
        return 'or';
    }
}
