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
function each(callable $predicate): Each
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
 * @return  \bovigo\assert\predicate\EachKey
 * @since   1.3.0
 */
function eachKey(callable $predicate): EachKey
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
function not(callable $predicate): Predicate
{
    return new NegatePredicate($predicate);
}

/**
 * returns predicate which tests if value is null
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 */
function isNull(): Predicate
{
    return IsNull::instance();
}

/**
 * returns predicate which tests that something is not null
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 */
function isNotNull(): Predicate
{
    return not(isNull());
}

/**
 * returns predicate which test that something is empty
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 */
function isEmpty(): Predicate
{
    return IsEmpty::instance();
}

/**
 * returns a predicate which tests that something is not empty
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 */
function isNotEmpty(): Predicate
{
    return not(isEmpty());
}

/**
 * returns predicate which tests for truthiness
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 */
function isTrue(): Predicate
{
    return IsTrue::instance();
}

/**
 * returns predicate which tests for falsiness
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 */
function isFalse(): Predicate
{
    return IsFalse::instance();
}

/**
 * returns predicate which tests for equality
 *
 * @api
 * @deprecated  param  $delta is deprecated, use withDelta() on returned Predicate instead, will be removed with 7.0
 * @param   mixed  $expected  expected value
 * @param   float  $delta     optional  allowed numerical distance between two values to consider them equal
 * @return  \bovigo\assert\predicate\Equals
 */
function equals($expected, float $delta = 0.0): Equals
{
    return new Equals($expected, $delta);
}

/**
 * returns predicate which tests for non-equality
 *
 * @api
 * @deprecated  param  $delta is deprecated, use withDelta() on returned Predicate instead, will be removed with 7.0
 * @param   mixed  $unexpected  expected value
 * @param   float  $delta       optional  allowed numerical distance between two values to consider them not equal
 * @return  \bovigo\assert\predicate\Delta&\bovigo\assert\predicate\Predicate
 */
function isNotEqualTo($unexpected, float $delta = 0.0): Delta
{
    return new class(equals($unexpected, $delta)) extends NegatePredicate implements Delta
    {
        /**
         * @var  Equals
         */
        private $equal;
        public function __construct(Equals $equal)
        {
            $this->equal = $equal;
            parent::__construct($equal);
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
 * @param   string  $expectedType  name of expected type
 * @return  \bovigo\assert\predicate\Predicate
 */
function isInstanceOf(string $expectedType): Predicate
{
    return new IsInstanceOf($expectedType);
}

/**
 * returns predicate which tests that something is not of given instance type
 *
 * @api
 * @param   string  $unexpectedType  name of expected type
 * @return  \bovigo\assert\predicate\Predicate
 */
function isNotInstanceOf(string $unexpectedType): Predicate
{
    return not(isInstanceOf($unexpectedType));
}

/**
 * returns predicate which tests for identity
 *
 * @api
 * @param   mixed  $expected  expected value
 * @return  \bovigo\assert\predicate\Predicate
 */
function isSameAs($expected): Predicate
{
    return new IsIdentical($expected);
}

/**
 * returns predicate which tests for non-identity
 *
 * @api
 * @param   mixed  $unexpected  expected value
 * @return  \bovigo\assert\predicate\Predicate
 */
function isNotSameAs($unexpected): Predicate
{
    return not(isSameAs($unexpected));
}

/**
 * returns predicate which tests for size
 *
 * @api
 * @param   int  $expectedSize  expected count size
 * @return  \bovigo\assert\predicate\Predicate
 */
function isOfSize(int $expectedSize): Predicate
{
    return new IsOfSize($expectedSize);
}

/**
 * returns predicate which tests something has not the size
 *
 * @api
 * @param   int  $unexpectedSize  count size which is not expected
 * @return  \bovigo\assert\predicate\Predicate
 */
function isNotOfSize(int $unexpectedSize): Predicate
{
    return not(isOfSize($unexpectedSize));
}

/**
 * returns predicate which tests something is an array
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isArray(): Predicate
{
  return isOfType('array');
}

/**
 * returns predicate which tests something is not an array
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
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
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isBool(): Predicate
{
  return isOfType('bool');
}

/**
 * returns predicate which tests something is not a boolean value
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNotBool(): Predicate
{
  return not(isBool());
}

/**
 * returns predicate which tests something is a float
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isFloat(): Predicate
{
  return isOfType('float');
}

/**
 * returns predicate which tests something is not a float
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNotFloat(): Predicate
{
  return not(isFloat());
}

/**
 * returns predicate which tests something is an integer
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isInt(): Predicate
{
  return isOfType('int');
}

/**
 * returns predicate which tests something is not an integer
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNotInt(): Predicate
{
  return not(isInt());
}

/**
 * returns predicate which tests something is a numeric value
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNumeric(): Predicate
{
  return isOfType('numeric');
}

/**
 * returns predicate which tests something is not a numeric value
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNotNumeric(): Predicate
{
  return not(isNumeric());
}

/**
 * returns predicate which tests something is an object
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isObject(): Predicate
{
  return isOfType('object');
}

/**
 * returns predicate which tests something is not an object
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNotAnObject(): Predicate
{
  return not(isObject());
}

/**
 * returns predicate which tests something is a resource
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isResource(): Predicate
{
  return isOfType('resource');
}

/**
 * returns predicate which tests something is not a resource
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNotAResource(): Predicate
{
  return not(isResource());
}

/**
 * returns predicate which tests something is a string
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isString(): Predicate
{
  return isOfType('string');
}

/**
 * returns predicate which tests something is not a string
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNotAString(): Predicate
{
  return not(isString());
}

/**
 * returns predicate which tests something is a scalar value
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isScalar(): Predicate
{
  return isOfType('scalar');
}

/**
 * returns predicate which tests something is not a scalar value
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNotScalar(): Predicate
{
  return not(isScalar());
}

/**
 * returns predicate which tests something is a callable
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isCallable(): Predicate
{
  return isOfType('callable');
}

/**
 * returns predicate which tests something is not a callable
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNotCallable(): Predicate
{
  return not(isCallable());
}

/**
 * returns predicate which tests something is iterable
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isIterable(): Predicate
{
  return isOfType('iterable');
}

/**
 * returns predicate which tests something is not iterable
 *
 * @api
 * @return  \bovigo\assert\predicate\Predicate
 * @since   5.0.0
 */
function isNotIterable(): Predicate
{
  return not(isIterable());
}

/**
 * returns predicate which tests something is a specific internal PHP type
 *
 * @api
 * @param   string  $expectedType  name of Predicate to test for
 * @return  \bovigo\assert\predicate\CallablePredicate
 * @throws  \InvalidArgumentException  in case expected type is unknown
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
        throw new \InvalidArgumentException(
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
 * @param   string  $unexpectedType  name of type to test for
 * @return  \bovigo\assert\predicate\Predicate
 */
function isNotOfType(string $unexpectedType): Predicate
{
    return not(isOfType($unexpectedType));
}

/**
 * returns predicate which tests something is greather than the expected value
 *
 * @api
 * @param   int|float  $expected
 * @return  \bovigo\assert\predicate\Predicate
 */
function isGreaterThan($expected): Predicate
{
    return new IsGreaterThan($expected);
}

/**
 * returns predicate which tests something is greater than or equal to the expected value
 *
 * @api
 * @param   int|float  $expected
 * @return  \bovigo\assert\predicate\Predicate
 */
function isGreaterThanOrEqualTo($expected): Predicate
{
    return equals($expected)->or(isGreaterThan($expected));
}

/**
 * returns predicate which tests something is smaller than the expected value
 *
 * @api
 * @param   int|float  $expected
 * @return  \bovigo\assert\predicate\Predicate
 */
function isLessThan($expected): Predicate
{
    return new IsLessThan($expected);
}

/**
 * returns predicate which tests something is smaller than or equal to the expected value
 *
 * @api
 * @param   int|float  $expected
 * @return  \bovigo\assert\predicate\Predicate
 */
function isLessThanOrEqualTo($expected): Predicate
{
    return equals($expected)->or(isLessThan($expected));
}

/**
 * returns predicate which tests that $needle is contained in a value
 *
 * @api
 * @param   mixed  $needle  value that must be contained
 * @return  \bovigo\assert\predicate\Predicate
 */
function contains($needle): Predicate
{
    return new Contains($needle);
}

/**
 * returns predicate which tests that $needle is not contained in a value
 *
 * @api
 * @param   mixed  $needle  value that must not be contained
 * @return  \bovigo\assert\predicate\Predicate
 */
function doesNotContain($needle): Predicate
{
    return not(contains($needle));
}

/**
 * returns predicate which tests that $key is the key of an element
 *
 * @api
 * @param   int|string  $key
 * @return  \bovigo\assert\predicate\Predicate
 */
function hasKey($key): Predicate
{
    return new HasKey($key);
}

/**
 * returns predicate which tests that $key is not the key of an element
 *
 * @api
 * @param   int|string  $key
 * @return  \bovigo\assert\predicate\Predicate
 */
function doesNotHaveKey($key): Predicate
{
    return not(hasKey($key));
}

/**
 * returns predicate which tests against a regular expression
 *
 * @api
 * @param   string  $pattern
 * @return  \bovigo\assert\predicate\Predicate
 */
function matches(string $pattern): Predicate
{
    return new Regex($pattern);
}

/**
 * returns predicate which tests against a regular expression
 *
 * @api
 * @param   string  $pattern
 * @return  \bovigo\assert\predicate\Predicate
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
 * @since   3.2.0
 * @api
 * @param   string  $format
 * @return  \bovigo\assert\predicate\Predicate
 */
function matchesFormat(string $format): Predicate
{
    return new StringMatchesFormat($format);
}

/**
 * returns predicate which tests against a string does not match a particular PHPUnit format expression
 *
 * @since   3.2.0
 * @api
 * @param   string  $format
 * @return  \bovigo\assert\predicate\Predicate
 */
function doesNotMatchFormat(string $format): Predicate
{
    return not(matchesFormat($format));
}

/**
 * returns predicate which tests whether a file exists
 *
 * @api
 * @param   string  $basePath  optional  base path where file must reside in
 * @return  \bovigo\assert\predicate\Predicate
 */
function isExistingFile(string $basePath = null): Predicate
{
    return new IsExistingFile($basePath);
}

/**
 * returns predicate which tests whether a file does not exist
 *
 * @api
 * @param   string  $basePath  optional  base path where file must not reside in
 * @return  \bovigo\assert\predicate\Predicate
 */
function isNonExistingFile(string $basePath = null): Predicate
{
    return not(isExistingFile($basePath));
}

/**
 * returns predicate which tests whether a directory exists
 *
 * @api
 * @param   string  $basePath  optional  base path where directory must reside in
 * @return  \bovigo\assert\predicate\Predicate
 */
function isExistingDirectory(string $basePath = null): Predicate
{
    return new IsExistingDirectory($basePath);
}

/**
 * returns predicate which tests whether a directory does not exist
 *
 * @api
 * @param   string  $basePath  optional  base path where directory must not reside in
 * @return  \bovigo\assert\predicate\Predicate
 */
function isNonExistingDirectory(string $basePath = null): Predicate
{
    return not(isExistingDirectory($basePath));
}

/**
 * returns predicate which tests that a string starts with given prefix
 *
 * @api
 * @param   string  $prefix
 * @return  \bovigo\assert\predicate\Predicate
 * @since   1.1.0
 */
function startsWith(string $prefix): Predicate
{
    return new class($prefix) extends Predicate
    {
        /**
         * @var  string
         */
        private $prefix;

        public function __construct(string $prefix)
        {
            $this->prefix = $prefix;
        }

        public function test($value): bool
        {
            if (!is_string($value)) {
                throw new \InvalidArgumentException(
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
 * @param   string  $prefix
 * @return  \bovigo\assert\predicate\Predicate
 * @since   1.1.0
 */
function doesNotStartWith(string $prefix): Predicate
{
    return not(startsWith($prefix));
}

/**
 * returns predicate which tests that a string ends with given suffix
 *
 * @api
 * @param   string  $suffix
 * @return  \bovigo\assert\predicate\Predicate
 * @since   1.1.0
 */
function endsWith(string $suffix): Predicate
{
    return new class($suffix) extends Predicate
    {
        /**
         * @var  string
         */
        private $suffix;

        public function __construct(string $suffix)
        {
            $this->suffix = $suffix;
        }

        public function test($value): bool
        {
            if (!is_string($value)) {
                throw new \InvalidArgumentException(
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
 * @param   string  $suffix
 * @return  \bovigo\assert\predicate\Predicate
 * @since   1.1.0
 */
function doesNotEndWith(string $suffix): Predicate
{
    return not(endsWith($suffix));
}
