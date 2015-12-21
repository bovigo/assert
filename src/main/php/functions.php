<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use function bovigo\assert\assert;
use function bovigo\assert\predicate\contains;
use function bovigo\assert\predicate\doesNotContain;
use function bovigo\assert\predicate\equals;
use function bovigo\assert\predicate\isFalse;
use function bovigo\assert\predicate\isGreaterThan;
use function bovigo\assert\predicate\isInstanceOf;
use function bovigo\assert\predicate\isLessThan;
use function bovigo\assert\predicate\isNotEqualTo;
use function bovigo\assert\predicate\isNotInstanceOf;
use function bovigo\assert\predicate\isNotNull;
use function bovigo\assert\predicate\isNotOfSize;
use function bovigo\assert\predicate\isNotOfType;
use function bovigo\assert\predicate\isNotSameAs;
use function bovigo\assert\predicate\isNull;
use function bovigo\assert\predicate\isOfSize;
use function bovigo\assert\predicate\isOfType;
use function bovigo\assert\predicate\isTrue;
use function bovigo\assert\predicate\isSameAs;

/**
 * asserts that an array has a key
 *
 * @param   int|string          $key      key which must be in array
 * @param   array|\ArrayAccess  $array    array which should have given key
 * @param   string              $message  optional  additional description for failure message
 * @return  bool
 */
function assertArrayHasKey($key, $array, $message = null)
{
    return assert($array, hasKey($key), $message);
}

/**
 * asserts that an array does not have a key
 *
 * @param   int|string          $key      key which must be in array
 * @param   array|\ArrayAccess  $array    array which should have given key
 * @param   string              $message  optional  additional description for failure message
 * @return  bool
 */
function assertArrayNotHasKey($key, $array, $message = null)
{
    return assert($array, doesNotHaveKey($key), $message);
}

/**
 * asserts that a haystack contains a needle
 *
 * @param   mixed                      $needle    what must be contained in haystack
 * @param   string|array|\Traversable  $haystack  where needle must be contained
 * @param   string                     $message   optional  additional description for failure message
 * @return  bool
 */
function assertContains($needle, $haystack, $message = null)
{
    return assert($haystack, contains($needle), $message);
}

/**
 * asserts that a haystack does not contain  needle
 *
 * @param   mixed                      $needle    what must not be contained in haystack
 * @param   string|array|\Traversable  $haystack  where needle must not be contained
 * @param   string                     $message   optional  additional description for failure message
 * @return  bool
 */
function assertNotContains($needle, $haystack, $message = null)
{
    return assert($haystack, doesNotContain($needle), $message);
}

/**
 * assers that a value is empty
 *
 * @param   mixed   $actual    value that must be empty
 * @param   string  $message   optional  additional description for failure message
 * @return  bool
 */
function assertEmpty($actual, $message = null)
{
    return assert($actual, isEmpty(), $message);
}

/**
 * assers that a value is empty
 *
 * @param   mixed   $actual    value that must be empty
 * @param   string  $message   optional  additional description for failure message
 * @return  bool
 */
function assertNotEmpty($actual, $message = null)
{
    return assert($actual, isNotEmpty(), $message);
}

/**
 * asserts a file does exist
 *
 * The file must either be in the current working directory, or a full path must
 * be specified with the filename.
 *
 * @param   string  $filename  name of file that must exist
 * @param   string  $message   optional  additional description for failure message
 * @return  bool
 */
function assertFileExists($filename, $message = null)
{
    return assert($filename, isExistingFile(), $message);
}

/**
 * asserts a file does exist
 *
 * The file must either be in the current working directory, or a full path must
 * be specified with the filename.
 *
 * @param   string  $filename  name of file that must exist
 * @param   string  $message   optional  additional description for failure message
 * @return  bool
 */
function assertFileNotExists($filename, $message = null)
{
    return assert($filename, isNonExistingFile(), $message);
}

/**
 * asserts that a value is smaller than another value
 *
 * @param   numeric  $expected  expected value
 * @param   mixed    $actual    value to test
 * @param   string   $message   optional  additional description for failure message
 * @return  bool
 */
function assertLessThan($expected, $actual, $message = null)
{
    return assert($actual, isLessThan($expected), $message);
}

/**
 * asserts that a value is smaller than or equal to another value
 *
 * @param   numeric  $expected  expected value
 * @param   mixed    $actual    value to test
 * @param   string   $message   optional  additional description for failure message
 * @return  bool
 */
function assertLessThanOrEqual($expected, $actual, $message = null)
{
    return assert($actual, isLessThanOrEqual($expected), $message);
}

/**
 * asserts that a value is greater than another value
 *
 * @param   numeric  $expected  expected value
 * @param   mixed    $actual    value to test
 * @param   string   $message   optional  additional description for failure message
 * @return  bool
 */
function assertGreaterThan($expected, $actual, $message = null)
{
    return assert($actual, isGreaterThan($expected), $message);
}

/**
 * asserts that a value is greater than or equal to another value
 *
 * @param   numeric  $expected  expected value
 * @param   mixed    $actual    value to test
 * @param   string   $message   optional  additional description for failure message
 * @return  bool
 */
function assertGreaterThanOrEqual($expected, $actual, $message = null)
{
    return assert($actual, isGreaterThanOrEqualTo($expected), $message);
}

/**
 * asserts that a variable is of a given internal PHP type which is not a class
 *
 * @param   mixed   $expectedType  expected type
 * @param   mixed   $actual        value to test
 * @param   string  $message       optional  additional description for failure message
 * @return  bool
 */
function assertInternalType($expectedType, $actual, $message = null)
{
    return assert($actual, isOfType($expectedType), $message);
}

/**
 * asserts that a variable is of a given internal PHP type which is not a class
 *
 * @param   mixed   $unexpectedType  type which is not expected
 * @param   mixed   $actual          value to test
 * @param   string  $message         optional  additional description for failure message
 * @return  bool
 */
function assertNotInternalType($unexpectedType, $actual, $message = null)
{
    return assert($actual, isNotOfType($unexpectedType), $message);
}

/**
 * asserts the number of elements of an array, Countable or Traversable
 *
 * @param   int                                   $expectedSize  expected count size
 * @param   string|array|\Countable|\Traversable  $countable     what to count
 * @param   string                                $message       optional  additional description for failure message
 * @return  bool
 */
function assertCount($expectedSize, $countable, $message = null)
{
    return assert($countable, isOfSize($expectedSize), $message);
}

/**
 * asserts the number of elements of an array, Countable or Traversable
 *
 * @param   int                                   $expectedSize  expected count size
 * @param   string|array|\Countable|\Traversable  $countable     what to count
 * @param   string                                $message       optional  additional description for failure message
 * @return  bool
 */
function assertNotCount($expectedSize, $countable, $message = null)
{
    return assert($countable, isNotOfSize($expectedSize), $message);
}

/**
 * asserts that two values are equal
 *
 * @param   mixed   $expected  expected value
 * @param   mixed   $actual    value to test
 * @param   string  $message   optional  additional description for failure message
 * @param   float   $delta     optional  allowed numerical distance between two values to consider them equal
 * @return  bool
 */
function assertEquals($expected, $actual, $message = null, $delta = 0.0)
{
    return assert($actual, equals($expected, $delta), $message);
}

/**
 * asserts that two values are not equal
 *
 * @param   mixed   $expected      expected value
 * @param   mixed   $actual        value to test
 * @param   string  $message       optional  additional description for failure message
 * @param   float   $delta         optional  allowed numerical distance between two values to consider them equal
 * @return  bool
 */
function assertNotEquals($expected, $actual, $message = null, $delta = 0.0)
{
    return assert($actual, isNotEqualTo($expected, $delta), $message);
}

/**
 * asserts that given value is false
 *
 * @param   mixed   $value    value to test
 * @param   string  $message  optional  additional description for failure message
 * @return  bool
 */
function assertFalse($value, $message = null)
{
    return assert($value, isFalse(), $message);
}

/**
 * asserts that given value is an instance of the expected type
 *
 * @param   string  $expectedType  name of expected type
 * @param   mixed   $actual        value to test
 * @param   string  $message       optional  additional description for failure message
 * @return  bool
 */
function assertInstanceOf($expectedType, $actual, $message = null)
{
    return assert($actual, isInstanceOf($expectedType), $message);
}

/**
 * asserts that given value is not an instance of the expected type
 *
 * @param   string  $expectedType  name of expected type
 * @param   mixed   $actual        value to test
 * @param   string  $message       optional  additional description for failure message
 * @return  bool
 */
function assertNotInstanceOf($expectedType, $actual, $message = null)
{
    return assert($actual, isNotInstanceOf($expectedType), $message);
}

/**
 * asserts that given value is null
 *
 * @param   mixed   $value    value to test
 * @param   string  $message  optional  additional description for failure message
 * @return  bool
 */
function assertNull($value, $message = null)
{
    return assert($value, isNull(), $message);
}

/**
 * asserts that given value is not null
 *
 * @param   mixed   $value    value to test
 * @param   string  $message  optional  additional description for failure message
 * @return  bool
 */
function assertNotNull($value, $message = null)
{
    return assert($value, isNotNull(), $message);
}

/**
 * asserts that both expected and actual reference the same value
 *
 * @param   mixed   $expected  expected value
 * @param   mixed   $actual    value to test
 * @param   string  $message   optional  additional description for failure message
 * @return  bool
 */
function assertSame($expected, $actual, $message = null)
{
    return assert($actual, isSameAs($expected), $message);
}

/**
 * asserts that both expected and actual do not reference the same value
 *
 * @param   mixed   $expected  expected value
 * @param   mixed   $actual    value to test
 * @param   string  $message   optional  additional description for failure message
 * @return  bool
 */
function assertNotSame($expected, $actual, $message = null)
{
    return assert($actual, isNotSameAs($expected), $message);
}

/**
 * asserts that a string matches a regular expression
 *
 * @param   string  $pattern   regular expression to match string with
 * @param   string  $string    string to match
 * @param   string  $message   optional  additional description for failure message
 * @return  bool
 */
function assertRegExp($pattern, $string, $message = null)
{
    return assert($string, matches($pattern), $message);
}

/**
 * asserts that a string does not match a regular expression
 *
 * @param   string  $pattern   regular expression to match string with
 * @param   string  $string    string to match
 * @param   string  $message   optional  additional description for failure message
 * @return  bool
 */
function assertNotRegExp($pattern, $string, $message = null)
{
    return assert($string, doesNotMatch($pattern), $message);
}

/**
 * asserts that given value is true
 *
 * @param   mixed   $value        value to test
 * @param   string  $description  optional  additional description for failure message
 * @return  bool
 */
function assertTrue($value, $description = null)
{
    return assert($value, isTrue(), $description);
}

/**
 * evaluates predicate agains value
 *
 * @param    mixed                                        $value     value to test
 * @param    \bovigo\assert\predicate\Predicate|callable  $expected  predicate or callable to test given value
 * @param    string                                       $message   optional  additional description for failure message
 * @return   bool
 */
function assertThat($value, $expected, $message = null)
{
    return assert($value, $expected, $message);
}