<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Trait for predicates which can be reused.
 */
trait ReusablePredicate
{
    /**
     * reusable instance
     *
     * @type  Predicate
     */
    private static $instance;

    /**
     * returns reusable predicate instance
     *
     * @return  \bovigo\assert\predicate\Predicate
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
