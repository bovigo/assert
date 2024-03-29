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
 * Trait for predicates which can be reused.
 *
 * @api
 */
trait ReusablePredicate
{
    /**
     * reusable instance
     *
     * @var Predicate
     */
    private static $instance;

    /**
     * returns reusable predicate instance
     */
    public static function instance(): Predicate
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
