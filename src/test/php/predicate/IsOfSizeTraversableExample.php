<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use Iterator;
/**
 * Helper class for the test.
 *
 * @implements \Iterator<int,int>
 */
class IsOfSizeTraversableExample implements Iterator
{
    /**
     * @var  int
     */
    private $current = 0;

    /**
     * @return  int
     */
    public function current(): mixed
    {
        return $this->current;
    }

    /**
     * @return  int
     */
    public function key(): mixed
    {
        return $this->current;
    }

    public function next(): void
    {
        $this->current++;
    }

    public function rewind(): void
    {
        $this->current = 0;
    }

    public function valid(): bool
    {
        return 3 > $this->current;
    }
}