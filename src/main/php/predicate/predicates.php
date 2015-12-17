<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

/**
 * returns predicate which tests if value is null
 *
 * @return  \bovigo\assert\predicate\IsNull
 */
function isNull()
{
    return IsNull::instance();
}

/**
 * returns predicate which tests that something is not null
 *
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotNull()
{
    return not(isNull());
}

/**
 * returns predicate which tests for truthiness
 *
 * @return  \bovigo\assert\predicate\IsFalse
 */
function isTrue()
{
    return IsTrue::instance();
}

/**
 * returns predicate which tests for falsiness
 *
 * @return  \bovigo\assert\predicate\IsFalse
 */
function isFalse()
{
    return isFalse::instance();
}

/**
 * returns predicate which tests for equality
 *
 * @param   mixed   $expected  expected value
 * @param   float   $delta     optional  allowed numerical distance between two values to consider them equal
 * @return  \bovigo\assert\predicate\Equals
 */
function equals($expected, $delta = 0.0)
{
    return new Equals($expected, $delta);
}

/**
 * returns predicate which tests for non-equality
 *
 * @param   mixed   $expected  expected value
 * @param   float   $delta     optional  allowed numerical distance between two values to consider them not equal
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotEqualTo($expected, $delta = 0.0)
{
    return not(equals($expected, $delta));
}

/**
 * negates the given predicate
 *
 * @param   \bovigo\assert\predicate\Predicate  $predicate
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function not(Predicate $predicate)
{
    return $predicate->negate();
}

/**
 * returns predicate which tests for instance type
 *
 * @param   string  $expectedType  name of expected type
 * @return  \bovigo\assert\predicate\IsInstanceOf
 */
function isInstanceOf($expectedType)
{
    return new IsInstanceOf($expectedType);
}

/**
 * returns predicate which tests that something is not of given instance type
 *
 * @param   string  $expectedType  name of expected type
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotInstanceOf($expectedType)
{
    return not(isInstanceOf($expectedType));
}

/**
 * returns predicate which tests for identity
 *
 * @param   mixed  $expected  expected value
 * @return  \bovigo\assert\predicate\IsIdentical
 */
function isSameAs($expected)
{
    return new IsIdentical($expected);
}

/**
 * returns predicate which tests for non-identity
 *
 * @param   mixed  $expected  expected value
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotSameAs($expected)
{
    return not(isSameAs($expected));
}
