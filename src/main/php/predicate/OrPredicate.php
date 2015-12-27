<?php
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
    public function test($value)
    {
        $exceptions = [];
        foreach ([$this->leftPredicate, $this->rightPredicate] as $predicate) {
            try {
                if ($predicate->test($value)) {
                    return true;
                }
            } catch (\Exception $ex) {
                $exceptions[] = $ex;
            }
        }
        
        switch (count($exceptions)) {
            case 0:
                return false;

            case 1:
                throw $exceptions[0];

            default:
                throw new \Exception(
                        $exceptions[0]->getMessage() . "\n" . $exceptions[1]->getMessage()
                );
        }
    }

    /**
     * returns combination operator as string
     *
     * @return  string
     */
    protected function operator()
    {
        return 'or';
    }
}
