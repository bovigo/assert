<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

/**
 * returns predicate which tests each value of something that is iterable
 *
 * Please note that an empty array or traversable will result in a successful
 * test. If it must not be empty use isNotEmpty()->and(each($predicate)).
 *
 * @api
 * @param   callable|\bovigo\assert\predicate\Predicate  $predicate
 * @return  \bovigo\assert\predicate\Each
 * @since   1.1.0
 */
function each($predicate)
{
    return new Each($predicate);
}

/**
 * returns predicate which tests each key of something that is iterable
 *
 * Please note that an empty array or traversable will result in a successful
 * test. If it must not be empty use isNotEmpty()->and(eachKey($predicate)).
 *
 * @api
 * @param   callable|\bovigo\assert\predicate\Predicate  $predicate
 * @return  \bovigo\assert\predicate\Each
 * @since   1.3.0
 */
function eachKey($predicate)
{
    return new EachKey($predicate);
}

/**
 * negates the given predicate
 *
 * @api
 * @param   callable|\bovigo\assert\predicate\Predicate  $predicate
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function not($predicate)
{
    return new NegatePredicate($predicate);
}

/**
 * returns predicate which tests if value is null
 *
 * @api
 * @return  \bovigo\assert\predicate\IsNull
 */
function isNull()
{
    return IsNull::instance();
}

/**
 * returns predicate which tests that something is not null
 *
 * @api
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotNull()
{
    return not(isNull());
}

/**
 * returns predicate which test that something is empty
 *
 * @api
 * @return  \bovigo\assert\predicate\IsEmpty
 */
function isEmpty()
{
    return IsEmpty::instance();
}

/**
 * returns a predicate which tests that something is not empty
 *
 * @api
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotEmpty()
{
    return not(isEmpty());
}

/**
 * returns predicate which tests for truthiness
 *
 * @api
 * @return  \bovigo\assert\predicate\IsFalse
 */
function isTrue()
{
    return IsTrue::instance();
}

/**
 * returns predicate which tests for falsiness
 *
 * @api
 * @return  \bovigo\assert\predicate\IsFalse
 */
function isFalse()
{
    return IsFalse::instance();
}

/**
 * returns predicate which tests for equality
 *
 * @api
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
 * @api
 * @param   mixed   $unexpected  expected value
 * @param   float   $delta       optional  allowed numerical distance between two values to consider them not equal
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotEqualTo($unexpected, $delta = 0.0)
{
    return not(equals($unexpected, $delta));
}

/**
 * returns predicate which tests for instance type
 *
 * @api
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
 * @api
 * @param   string  $unexpectedType  name of expected type
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotInstanceOf($unexpectedType)
{
    return not(isInstanceOf($unexpectedType));
}

/**
 * returns predicate which tests for identity
 *
 * @api
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
 * @api
 * @param   mixed  $unexpected  expected value
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotSameAs($unexpected)
{
    return not(isSameAs($unexpected));
}

/**
 * returns predicate which tests for size
 *
 * @api
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
 * @api
 * @param   int  $unexpectedSize  count size which is not expected
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNotOfSize($unexpectedSize)
{
    return not(isOfSize($unexpectedSize));
}

/**
 * returns predicate which tests something is a specific internal PHP type
 *
 * @api
 * @param   string  $expectedType  name of type to test for
 * @return  \bovigo\assert\predicate\CallablePredicate
 * @throws  \InvalidArgumentException  in case expected type is unknown
 */
function isOfType($expectedType)
{
    static $types = [
            'array'    => 'is_array',
            'boolean'  => 'is_bool',
            'bool'     => 'is_bool',
            'double'   => 'is_float',
            'float'    => 'is_float',
            'integer'  => 'is_integer',
            'int'      => 'is_integer',
            'numeric'  => 'is_numeric',
            'object'   => 'is_object',
            'resource' => 'is_resource',
            'string'   => 'is_string',
            'scalar'   => 'is_scalar',
            'callable' => 'is_callable'
    ];
    if (!isset($types[$expectedType])) {
        throw new \InvalidArgumentException(
                'Unknown internal type ' . $expectedType
        );
    }

    return new CallablePredicate(
            $types[$expectedType],
            'is of type "' . $expectedType . '"'
    );
}

/**
 * returns predicate which tests something is not a specific internal PHP type
 *
 * @api
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
 * @api
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
 * @api
 * @param   numeric  $expected
 * @return  \bovigo\assert\predicate\OrPredicate
 */
function isGreaterThanOrEqualTo($expected)
{
    return equals($expected)->or(isGreaterThan($expected));
}

/**
 * returns predicate which tests something is smaller than the expected value
 *
 * @api
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
 * @api
 * @param   numeric  $expected
 * @return  \bovigo\assert\predicate\OrPredicate
 */
function isLessThanOrEqualTo($expected)
{
    return equals($expected)->or(isLessThan($expected));
}

/**
 * returns predicate which tests that $needle is contained in a value
 *
 * @api
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
 * @api
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
 * @api
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
 * @api
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
 * @api
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
 * @api
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
 * @api
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
 * @api
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
 * @api
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
 * @api
 * @param   string  $basePath  optional  base path where directory must not reside in
 * @return  \bovigo\assert\predicate\NegatePredicate
 */
function isNonExistingDirectory($basePath = null)
{
    return not(isExistingDirectory($basePath));
}

/**
 * returns predicate which tests that a string starts with given prefix
 *
 * @api
 * @param   string  $prefix
 * @return  \bovigo\assert\predicate\CallablePredicate
 * @throws  \InvalidArgumentException
 * @since   1.1.0
 */
function startsWith($prefix)
{
    if (!is_string($prefix)) {
        throw new \InvalidArgumentException(
                'Prefix must be a string, "' . gettype($prefix) . '" given.'
        );
    }

    return new CallablePredicate(
            function($value) use ($prefix)
            {
                if (!is_string($value)) {
                    throw new \InvalidArgumentException(
                            'Given value is not a string, but of type "'
                            . gettype($value) . '"'
                    );
                }

                return 0 === substr_compare(
                        $value,
                        $prefix,
                        0,
                        strlen($prefix)
                );
            },
            'starts with \'' . $prefix . '\''
    );
}

/**
 * returns a predicate which tests that a string does not start with given prefix
 *
 * @api
 * @param   string  $prefix
 * @return  \bovigo\assert\predicate\NegatePredicate
 * @since   1.1.0
 */
function doesNotStartWith($prefix)
{
    return not(startsWith($prefix));
}

/**
 * returns predicate which tests that a string ends with given suffix
 *
 * @api
 * @param   string  $suffix
 * @return  \bovigo\assert\predicate\CallablePredicate
 * @throws  \InvalidArgumentException
 * @since   1.1.0
 */
function endsWith($suffix)
{
    if (!is_string($suffix)) {
        throw new \InvalidArgumentException(
                'Suffix must be a string, "' . gettype($suffix) . '" given.'
        );
    }

    return new CallablePredicate(
            function($value) use ($suffix)
            {
                if (!is_string($value)) {
                    throw new \InvalidArgumentException(
                            'Given value is not a string, but of type "'
                            . gettype($value) . '"'
                    );
                }

                return 0 === substr_compare(
                        $value,
                        $suffix,
                        -strlen($suffix)
                );
            },
            'ends with \'' . $suffix . '\''
    );
}

/**
 * returns a predicate which tests that a string does not end  with given suffix
 *
 * @api
 * @param   string  $suffix
 * @return  \bovigo\assert\predicate\NegatePredicate
 * @since   1.1.0
 */
function doesNotEndWith($suffix)
{
    return not(endsWith($suffix));
}

/**
 * returns a predicate which tests that a specific piece of code throws an exception
 *
 * @api
 * @param   string  $expectedClass  optional  type of exception to be thrown
 * @return  \bovigo\assert\predicate\ExpectedException
 * @since   1.6.0
 */
function throws($expectedClass = null)
{
    return new ExpectedException($expectedClass);
}
