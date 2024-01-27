<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;

use InvalidArgumentException;

/**
 * returns predicate which tests each value of something that is iterable
 *
 * Please note that an empty array or traversable will result in a successful
 * test. If it must not be empty use isNotEmpty()->and(each($predicate)).
 *
 * @api
 * @since 1.1.0
 */
function each(Predicate|callable $predicate): Each
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
 * @since 1.3.0
 */
function eachKey(Predicate|callable $predicate): EachKey
{
    return new EachKey($predicate);
}

/**
 * negates the given predicate
 *
 * @api
 */
function not(Predicate|callable $predicate): Predicate
{
    return new NegatePredicate($predicate);
}

/**
 * returns predicate which tests if value is null
 *
 * @api
 */
function isNull(): Predicate
{
    return IsNull::instance();
}

/**
 * returns predicate which tests that something is not null
 *
 * @api
 */
function isNotNull(): Predicate
{
    return not(isNull());
}

/**
 * returns predicate which test that something is empty
 *
 * @api
 */
function isEmpty(): Predicate
{
    return IsEmpty::instance();
}

/**
 * returns a predicate which tests that something is not empty
 *
 * @api
 */
function isNotEmpty(): Predicate
{
    return not(isEmpty());
}

/**
 * returns predicate which tests for truthiness
 *
 * @api
 */
function isTrue(): Predicate
{
    return IsTrue::instance();
}

/**
 * returns predicate which tests for falsiness
 *
 * @api
 */
function isFalse(): Predicate
{
    return IsFalse::instance();
}

/**
 * returns predicate which tests for equality
 *
 * @api
 */
function equals($expected): Equals
{
    return new Equals($expected);
}

/**
 * returns predicate which tests for non-equality
 *
 * @api
 */
function isNotEqualTo($unexpected): Predicate&Delta
{
    return new class(equals($unexpected)) extends NegatePredicate implements Delta
    {
        public function __construct(private Equals $equal)
        {
            parent::__construct($this->equal);
        }

        public function withDelta(float $delta): Predicate
        {
            $this->equal->withDelta($delta);
            return $this;
        }
    };
}

/**
 * returns predicate which tests for instance type
 *
 * @api
 */
function isInstanceOf(string $expectedType): Predicate
{
    return new IsInstanceOf($expectedType);
}

/**
 * returns predicate which tests that something is not of given instance type
 *
 * @api
 */
function isNotInstanceOf(string $unexpectedType): Predicate
{
    return not(isInstanceOf($unexpectedType));
}

/**
 * returns predicate which tests for identity
 *
 * @api
 */
function isSameAs(mixed $expected): Predicate
{
    return new IsIdentical($expected);
}

/**
 * returns predicate which tests for non-identity
 *
 * @api
 */
function isNotSameAs(mixed $unexpected): Predicate
{
    return not(isSameAs($unexpected));
}

/**
 * returns predicate which tests for size
 *
 * @api
 */
function isOfSize(int $expectedSize): Predicate
{
    return new IsOfSize($expectedSize);
}

/**
 * returns predicate which tests something has not the size
 *
 * @api
 */
function isNotOfSize(int $unexpectedSize): Predicate
{
    return not(isOfSize($unexpectedSize));
}

/**
 * returns predicate which tests something is an array
 *
 * @api
 * @since 5.0.0
 */
function isArray(): Predicate
{
  return isOfType('array');
}

/**
 * returns predicate which tests something is not an array
 *
 * @api
 * @since 5.0.0
 */
function isNotAnArray(): Predicate
{
  return not(isArray());
}

/**
 * returns predicate which tests for array subset
 *
 * @api
 * @param array<mixed> $other
 * @since 6.2.0
 */
function containsSubset(array $other): ContainsSubset
{
    return new ContainsSubset($other);
}

/**
 * returns predicate which tests something is a boolean value
 *
 * @api
 * @since 5.0.0
 */
function isBool(): Predicate
{
  return isOfType('bool');
}

/**
 * returns predicate which tests something is not a boolean value
 *
 * @api
 * @since 5.0.0
 */
function isNotBool(): Predicate
{
  return not(isBool());
}

/**
 * returns predicate which tests something is a float
 *
 * @api
 * @since 5.0.0
 */
function isFloat(): Predicate
{
  return isOfType('float');
}

/**
 * returns predicate which tests something is not a float
 *
 * @api
 * @since 5.0.0
 */
function isNotFloat(): Predicate
{
  return not(isFloat());
}

/**
 * returns predicate which tests something is an integer
 *
 * @api
 * @since 5.0.0
 */
function isInt(): Predicate
{
  return isOfType('int');
}

/**
 * returns predicate which tests something is not an integer
 *
 * @api
 * @since 5.0.0
 */
function isNotInt(): Predicate
{
  return not(isInt());
}

/**
 * returns predicate which tests something is a numeric value
 *
 * @api
 * @since 5.0.0
 */
function isNumeric(): Predicate
{
  return isOfType('numeric');
}

/**
 * returns predicate which tests something is not a numeric value
 *
 * @api
 * @since 5.0.0
 */
function isNotNumeric(): Predicate
{
  return not(isNumeric());
}

/**
 * returns predicate which tests something is an object
 *
 * @api
 * @since 5.0.0
 */
function isObject(): Predicate
{
  return isOfType('object');
}

/**
 * returns predicate which tests something is not an object
 *
 * @api
 * @since 5.0.0
 */
function isNotAnObject(): Predicate
{
  return not(isObject());
}

/**
 * returns predicate which tests something is a resource
 *
 * @api
 * @since 5.0.0
 */
function isResource(): Predicate
{
  return isOfType('resource');
}

/**
 * returns predicate which tests something is not a resource
 *
 * @api
 * @since 5.0.0
 */
function isNotAResource(): Predicate
{
  return not(isResource());
}

/**
 * returns predicate which tests something is a string
 *
 * @api
 * @since 5.0.0
 */
function isString(): Predicate
{
  return isOfType('string');
}

/**
 * returns predicate which tests something is not a string
 *
 * @api
 * @since 5.0.0
 */
function isNotAString(): Predicate
{
  return not(isString());
}

/**
 * returns predicate which tests something is a scalar value
 *
 * @api
 * @since 5.0.0
 */
function isScalar(): Predicate
{
  return isOfType('scalar');
}

/**
 * returns predicate which tests something is not a scalar value
 *
 * @api
 * @since 5.0.0
 */
function isNotScalar(): Predicate
{
  return not(isScalar());
}

/**
 * returns predicate which tests something is a callable
 *
 * @api
 * @since 5.0.0
 */
function isCallable(): Predicate
{
  return isOfType('callable');
}

/**
 * returns predicate which tests something is not a callable
 *
 * @api
 * @since 5.0.0
 */
function isNotCallable(): Predicate
{
  return not(isCallable());
}

/**
 * returns predicate which tests something is iterable
 *
 * @api
 * @since 5.0.0
 */
function isIterable(): Predicate
{
  return isOfType('iterable');
}

/**
 * returns predicate which tests something is not iterable
 *
 * @api
 * @since 5.0.0
 */
function isNotIterable(): Predicate
{
  return not(isIterable());
}

/**
 * returns predicate which tests something is a specific internal PHP type
 *
 * @api
 * @throws InvalidArgumentException in case expected type is unknown
 */
function isOfType(string $expectedType): Predicate
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
        'callable' => 'is_callable',
        'iterable' => 'is_iterable'
    ];
    if (!isset($types[$expectedType])) {
        throw new InvalidArgumentException(
            'Unknown internal type ' . $expectedType
        );
    }

    // create instance, but only if not done yet
    if (is_string($types[$expectedType])) {
        $types[$expectedType] = new CallablePredicate(
            $types[$expectedType],
            'is of type "' . $expectedType . '"'
        );
    }

    return $types[$expectedType];
}

/**
 * returns predicate which tests something is not a specific internal PHP type
 *
 * @api
 */
function isNotOfType(string $unexpectedType): Predicate
{
    return not(isOfType($unexpectedType));
}

/**
 * returns predicate which tests something is greather than the expected value
 *
 * @api
 */
function isGreaterThan(int|float $expected): Predicate
{
    return new IsGreaterThan($expected);
}

/**
 * returns predicate which tests something is greater than or equal to the expected value
 *
 * @api
 */
function isGreaterThanOrEqualTo(int|float $expected): Predicate
{
    return equals($expected)->or(isGreaterThan($expected));
}

/**
 * returns predicate which tests something is smaller than the expected value
 *
 * @api
 */
function isLessThan(int|float $expected): Predicate
{
    return new IsLessThan($expected);
}

/**
 * returns predicate which tests something is smaller than or equal to the expected value
 *
 * @api
 */
function isLessThanOrEqualTo(int|float $expected): Predicate
{
    return equals($expected)->or(isLessThan($expected));
}

/**
 * returns predicate which tests that $needle is contained in a value
 *
 * @api
 */
function contains(mixed $needle): Predicate
{
    return new Contains($needle);
}

/**
 * returns predicate which tests that $needle is not contained in a value
 *
 * @api
 */
function doesNotContain(mixed $needle): Predicate
{
    return not(contains($needle));
}

/**
 * returns predicate which tests that $key is the key of an element
 *
 * @api
 */
function hasKey(int|string $key): Predicate
{
    return new HasKey($key);
}

/**
 * returns predicate which tests that $key is not the key of an element
 *
 * @api
 */
function doesNotHaveKey(int|string $key): Predicate
{
    return not(hasKey($key));
}

/**
 * returns predicate which tests against a regular expression
 *
 * @api
 */
function matches(string $pattern): Predicate
{
    return new Regex($pattern);
}

/**
 * returns predicate which tests against a regular expression
 *
 * @api
 */
function doesNotMatch(string $pattern): Predicate
{
    return not(matches($pattern));
}

/**
 * returns predicate which tests against a PHPUnit format expression
 *
 * Format can contain the following expressions:
 * %e: directory separator, for example / on Linux
 * %s: one or more of anything (character or white space) except the end of line character
 * %S: zero or more of anything (character or white space) except the end of line character
 * %a: one or more of anything (character or white space) including the end of line character
 * %A: zero or more of anything (character or white space) including the end of line character
 * %w: zero or more white space characters
 * %i: signed integer value, for example +3142, -3142
 * %d: unsigned integer value, for example 123456
 * %x: one or more hexadecimal character. That is, characters in the range 0-9, a-f, A-F
 * %f: floating point number, for example: 3.142, -3.142, 3.142E-10, 3.142e+10
 * %c: single character of any sort
 *
 * @since 3.2.0
 * @api
 */
function matchesFormat(string $format): Predicate
{
    return new StringMatchesFormat($format);
}

/**
 * returns predicate which tests against a string does not match a particular PHPUnit format expression
 *
 * @since 3.2.0
 * @api
 */
function doesNotMatchFormat(string $format): Predicate
{
    return not(matchesFormat($format));
}

/**
 * returns predicate which tests whether a file exists
 *
 * In case basePath is ommitted the check is against the root of the file system.
 *
 * @api
 */
function isExistingFile(string $basePath = null): Predicate
{
    return new IsExistingFile($basePath);
}

/**
 * returns predicate which tests whether a file does not exist
 *
 * In case basePath is ommitted the check is against the root of the file system.
 *
 * @api
 */
function isNonExistingFile(string $basePath = null): Predicate
{
    return not(isExistingFile($basePath));
}

/**
 * returns predicate which tests whether a directory exists
 *
 * In case basePath is ommitted the check is against the root of the file system.
 *
 * @api
 */
function isExistingDirectory(string $basePath = null): Predicate
{
    return new IsExistingDirectory($basePath);
}

/**
 * returns predicate which tests whether a directory does not exist
 *
 * In case basePath is ommitted the check is against the root of the file system.
 *
 * @api
 */
function isNonExistingDirectory(string $basePath = null): Predicate
{
    return not(isExistingDirectory($basePath));
}

/**
 * returns predicate which tests that a string starts with given prefix
 *
 * @api
 * @since 1.1.0
 */
function startsWith(string $prefix): Predicate
{
    return new class($prefix) extends Predicate
    {
        public function __construct(private string $prefix) { }

        public function test(mixed $value): bool
        {
            if (!is_string($value)) {
                throw new InvalidArgumentException(
                    'Given value is not a string, but of type "'
                    . gettype($value) . '"'
                );
            }

            return 0 === substr_compare(
                $value,
                $this->prefix,
                0,
                strlen($this->prefix)
            );
        }

        public function __toString(): string
        {
            return 'starts with \'' . $this->prefix . '\'';
        }
    };
}

/**
 * returns a predicate which tests that a string does not start with given prefix
 *
 * @api
 * @since 1.1.0
 */
function doesNotStartWith(string $prefix): Predicate
{
    return not(startsWith($prefix));
}

/**
 * returns predicate which tests that a string ends with given suffix
 *
 * @api
 * @since 1.1.0
 */
function endsWith(string $suffix): Predicate
{
    return new class($suffix) extends Predicate
    {
        public function __construct(private string $suffix) { }

        public function test(mixed $value): bool
        {
            if (!is_string($value)) {
                throw new InvalidArgumentException(
                    'Given value is not a string, but of type "'
                    . gettype($value) . '"'
                );
            }

            return 0 === substr_compare(
                $value,
                $this->suffix,
                -strlen($this->suffix)
            );
        }

        public function __toString(): string
        {
            return 'ends with \'' . $this->suffix . '\'';
        }
    };
}

/**
 * returns a predicate which tests that a string does not end  with given suffix
 *
 * @api
 * @since 1.1.0
 */
function doesNotEndWith(string $suffix): Predicate
{
    return not(endsWith($suffix));
}
