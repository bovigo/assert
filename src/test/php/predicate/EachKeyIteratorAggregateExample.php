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
 * Helper class for the test.
 *
 * @implements \IteratorAggregate<int,int|string>
 */
class EachKeyIteratorAggregateExample implements \IteratorAggregate
{
    private $iterator;

    public function __construct()
    {
        $this->iterator = new \ArrayIterator([303, 313, 'foo']);
    }

    /**
     * @return  \Iterator<int,int|string>
     */
    public function getIterator(): \Iterator
    {
        return $this->iterator;
    }
}