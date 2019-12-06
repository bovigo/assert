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
 * @implements \Iterator<int,int>
 */
class IsOfSizeTraversableExample implements \Iterator
{
    /**
     * @var  int
     */
    private $current = 0;

    /**
     * @return  int
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * @return  int
     */
    public function key()
    {
        return $this->current;
    }

    /**
     * @return  void
     */
    public function next()
    {
        $this->current++;
    }

    /**
     * @return  void
     */
    public function rewind()
    {
        $this->current = 0;
    }

    /**
     * @return  bool
     */
    public function valid()
    {
        return 3 > $this->current;
    }
}