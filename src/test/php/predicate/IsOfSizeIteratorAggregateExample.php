<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use ArrayIterator;
use IteratorAggregate;
use Traversable;
/**
 * Helper class for the test.
 *
 * @implements \IteratorAggregate<int,int>
 */
class IsOfSizeIteratorAggregateExample implements IteratorAggregate
{
    public function getIterator(): Traversable
    {
        return new ArrayIterator([1, 2, 3]);
    }
}