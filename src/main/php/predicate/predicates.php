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
 * returns predicate which test that something is empty
 *
 * @return  \bovigo\assert\predicate\IsEmpty
 */
function isEmpty()
{
    return IsEmpty::instance();
}

/**
 * returns a predicate which tests that something is not empty
 *
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotEmpty()
{
    return not(isEmpty());
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
    return IsFalse::instance();
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

/**
 * returns predicate which tests for size
 *
 * @param   int  $expectedSize  expected count size
 * @return  \bovigo\assert\predicate\IsOfSize
 */
function isOfSize($expectedSize)
{
    return new IsOfSize($expectedSize);
}

/**
 * returns predicate which tests something has not the size
 *
 * @param   int  $nonExpectedSize  count size which is not expected
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotOfSize($nonExpectedSize)
{
    return not(isOfSize($nonExpectedSize));
}

/**
 * returns predicate which tests something is a specific internal PHP type
 *
 * @param   string  $expectedType  name of type to test for
 * @return  \bovigo\assert\predicate\IsOfType
 */
function isOfType($expectedType)
{
    return new IsOfType($expectedType);
}

/**
 * returns predicate which tests something is not a specific internal PHP type
 *
 * @param   string  $unexpectedType  name of type to test for
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotOfType($unexpectedType)
{
    return not(isOfType($unexpectedType));
}

/**
 * returns predicate which tests something is greather than the expected value
 *
 * @param   numeric  $expected
 * @return  \bovigo\assert\predicate\IsGreaterThan
 */
function isGreaterThan($expected)
{
    return new IsGreaterThan($expected);
}

/**
 * returns predicate which tests something is greater than or equal to the expected value
 *
 * @param   numeric  $expected
 * @return  \bovigo\assert\predicate\OrPredicate
 */
function isGreaterThanOrEqualTo($expected)
{
    return isGreaterThan($expected)->orElse(equals($expected));
}

/**
 * returns predicate which tests something is smaller than the expected value
 *
 * @param   numeric  $expected
 * @return  \bovigo\assert\predicate\IsLessThan
 */
function isLessThan($expected)
{
    return new IsLessThan($expected);
}

/**
 * returns predicate which tests something is smaller than or equal to the expected value
 *
 * @param   numeric  $expected
 * @return  \bovigo\assert\predicate\OrPredicate
 */
function isLessThanOrEqual($expected)
{
    return isLessThan($expected)->orElse(equals($expected));
}

/**
 * returns predicate which tests that $needle is contained in a value
 *
 * @param   mixed  $needle  value that must be contained
 * @return  \bovigo\assert\predicate\Contains
 */
function contains($needle)
{
    return new Contains($needle);
}

/**
 * returns predicate which tests that $needle is not contained in a value
 *
 * @param   mixed  $needle  value that must not be contained
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function doesNotContain($needle)
{
    return not(contains($needle));
}

/**
 * returns predicate which tests that $key is the key of an element
 *
 * @param   int|string  $key
 * @return  \bovigo\assert\predicate\HasKey
 */
function hasKey($key)
{
    return new HasKey($key);
}

/**
 * returns predicate which tests that $key is not the key of an element
 *
 * @param   int|string  $key
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function doesNotHaveKey($key)
{
    return not(hasKey($key));
}

/**
 * returns predicate which tests against a regular expression
 *
 * @param   string  $pattern
 * @return  \bovigo\assert\predicate\Regex
 */
function matches($pattern)
{
    return new Regex($pattern);
}

/**
 * returns predicate which tests against a regular expression
 *
 * @param   string  $pattern
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function doesNotMatch($pattern)
{
    return not(matches($pattern));
}

/**
 * returns predicate which tests whether a file exists
 *
 * @param   string  $basePath  optional  base path where file must reside in
 * @return  \bovigo\assert\predicate\IsExistingFile
 */
function isExistingFile($basePath = null)
{
    return new IsExistingFile($basePath);
}

/**
 * returns predicate which tests whether a file does not exist
 *
 * @param   string  $basePath  optional  base path where file must not reside in
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNonExistingFile($basePath = null)
{
    return not(isExistingFile($basePath));
}

/**
 * returns predicate which tests whether a directory exists
 *
 * @param   string  $basePath  optional  base path where directory must reside in
 * @return  \bovigo\assert\predicate\IsExistingFile
 */
function isExistingDirectory($basePath = null)
{
    return new IsExistingDirectory($basePath);
}

/**
 * returns predicate which tests whether a directory does not exist
 *
 * @param   string  $basePath  optional  base path where directory must not reside in
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNonExistingDirectory($basePath = null)
{
    return not(isExistingDirectory($basePath));
}
