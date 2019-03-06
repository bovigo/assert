<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Portions of this file were adapted from PHPUnit with the following license:
 *
 * PHPUnit
 *
 * Copyright (c) 2001-2015, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *  * Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 *  * Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *
 *  * Neither the name of Sebastian Bergmann nor the names of his
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */
namespace bovigo\assert\phpunit;
use PHPUnit\Framework\TestCase as Original;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Util\Type;

use function bovigo\assert\{
    assertThat,
    assertEmpty,
    assertFalse,
    assertNotEmpty,
    assertNotNull,
    assertNull,
    assertTrue
};
use function bovigo\assert\predicate\{
    contains,
    doesNotContain,
    doesNotEndWith,
    doesNotHaveKey,
    doesNotMatch,
    doesNotStartWith,
    each,
    endsWith,
    equals,
    hasKey,
    isExistingDirectory,
    isExistingFile,
    isGreaterThan,
    isGreaterThanOrEqualTo,
    isInstanceOf,
    isLessThan,
    isLessThanOrEqualTo,
    isNonExistingDirectory,
    isNonExistingFile,
    isNotEqualTo,
    isNotInstanceOf,
    isNotOfSize,
    isNotOfType,
    isNotSameAs,
    isOfSize,
    isOfType,
    isSameAs,
    matches,
    matchesFormat,
    startsWith
};
/**
 * Compatibility layer to use bovigo/assert in $this->assert*() style.
 *
 * Please note that it only overlays constraints supported by bovigo/assert. All
 * other constraints are PHPUnit's original constraints.
 *
 * @api
 */
abstract class TestCase extends Original
{
    /**
     * Asserts that an array has a specified key.
     *
     * @param mixed             $key
     * @param array|ArrayAccess $array
     * @param string            $message
     */
    public static function assertArrayHasKey($key, $array, string $message = ''): void
    {
        assertThat($array, hasKey($key), $message);
    }

    /**
     * Asserts that an array does not have a specified key.
     *
     * @param mixed             $key
     * @param array|ArrayAccess $array
     * @param string            $message
     */
    public static function assertArrayNotHasKey($key, $array, string $message = ''): void
    {
        assertThat($array, doesNotHaveKey($key), $message);
    }

    /**
     * Asserts that a haystack contains a needle.
     *
     * Please note that setting $ignoreCase, $checkForObjectIdentity or
     * $checkForNonObjectIdentity to a non-default value will cause a fallback
     * to PHPUnit's constraint.
     *
     * @param mixed  $needle
     * @param mixed  $haystack
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     */
    public static function assertContains($needle, $haystack, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false): void
    {
        if (true === $ignoreCase || false === $checkForObjectIdentity || true === $checkForNonObjectIdentity) {
            parent::assertContains($needle, $haystack, $message, $ignoreCase, $checkForObjectIdentity, $checkForNonObjectIdentity);
        } else {
            assertThat($haystack, contains($needle), $message);
        }
    }

    /**
     * Asserts that a haystack does not contain a needle.
     *
     * Please note that setting $ignoreCase, $checkForObjectIdentity or
     * $checkForNonObjectIdentity to a non-default value will cause a fallback
     * to PHPUnit's constraint.
     *
     * @param mixed  $needle
     * @param mixed  $haystack
     * @param string $message
     * @param bool   $ignoreCase
     * @param bool   $checkForObjectIdentity
     * @param bool   $checkForNonObjectIdentity
     */
    public static function assertNotContains($needle, $haystack, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false): void
    {
        if (true === $ignoreCase || false === $checkForObjectIdentity || true === $checkForNonObjectIdentity) {
            parent::assertNotContains($needle, $haystack, $message, $ignoreCase, $checkForObjectIdentity, $checkForNonObjectIdentity);
        } else {
            assertThat($haystack, doesNotContain($needle), $message);
        }
    }

    /**
     * Asserts that a haystack contains only values of a given type.
     *
     * @param  string             $type
     * @param  array|Traversable  $haystack
     * @param  bool               $isNativeType
     * @param  string             $message
     * @since  1.1.0
     */
    public static function assertContainsOnly(string $type, iterable $haystack, ?bool $isNativeType = null, string $message = ''): void
    {
        if (null === $isNativeType) {
            $isNativeType = Type::isType($type);
        }

        if (false === $isNativeType) {
            self::assertContainsOnlyInstancesOf($type, $haystack, $message);
        } else {
            assertThat($haystack, each(isOfType($type)), $message);
        }
    }

    /**
     * Asserts that a haystack contains only instances of a given classname
     *
     * @param  string            $classname
     * @param  array|Traversable $haystack
     * @param  string            $message
     * @since  1.1.0
     */
    public static function assertContainsOnlyInstancesOf(string $classname, iterable $haystack, string $message = ''): void
    {
        assertThat($haystack, each(isInstanceOf($classname)), $message);
    }

    /**
     * Asserts that a haystack does not contain only values of a given type.
     *
     * @param  string             $type
     * @param  array|Traversable  $haystack
     * @param  bool               $isNativeType
     * @param  string             $message
     * @since  1.1.0
     */
    public static function assertNotContainsOnly(string $type, iterable $haystack, ?bool $isNativeType = null, string $message = ''): void
    {
        if (null === $isNativeType) {
            $isNativeType = Type::isType($type);
        }

        assertThat(
                $haystack,
                each(false === $isNativeType ? isNotInstanceOf($type) : isNotOfType($type)),
                $message
        );
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param int    $expectedCount
     * @param mixed  $haystack
     * @param string $message
     */
    public static function assertCount(int $expectedCount, $haystack, string $message = ''): void
    {
        assertThat($haystack, isOfSize($expectedCount), $message);
    }

    /**
     * Asserts the number of elements of an array, Countable or Traversable.
     *
     * @param int    $expectedCount
     * @param mixed  $haystack
     * @param string $message
     */
    public static function assertNotCount(int $expectedCount, $haystack, string $message = ''): void
    {
        assertThat($haystack, isNotOfSize($expectedCount), $message);
    }

    /**
     * Asserts that two variables are equal.
     *
     * Please note that setting $canonicalize or $ignoreCase to true will cause
     * a fallback to PHPUnit's constraint.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth      ignored, same as in PHPUnit
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     */
    public static function assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false): void
    {
        if (true === $canonicalize || true === $ignoreCase) {
            parent::assertEquals($expected, $actual, $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
        } else {
            assertThat($actual, equals($expected, $delta), $message);
        }
    }

    /**
     * Asserts that two variables are not equal.
     *
     * Please note that setting $canonicalize or $ignoreCase to true will cause
     * a fallback to PHPUnit's constraint.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     * @param float  $delta
     * @param int    $maxDepth      ignored, same as in PHPUnit
     * @param bool   $canonicalize
     * @param bool   $ignoreCase
     */
    public static function assertNotEquals($expected, $actual, string $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false): void
    {
        if (true === $canonicalize || true === $ignoreCase) {
            parent::assertNotEquals($expected, $actual, $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
        } else {
            assertThat($actual, isNotEqualTo($expected, $delta), $message);
        }
    }

    /**
     * Asserts that a variable is empty.
     *
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertEmpty($actual, string $message = ''): void
    {
        assertEmpty($actual, $message);
    }

    /**
     * Asserts that a variable is not empty.
     *
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertNotEmpty($actual, string $message = ''): void
    {
        assertNotEmpty($actual, $message);
    }

    /**
     * Asserts that a value is greater than another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertGreaterThan($expected, $actual, string $message = ''): void
    {
        assertThat($actual, isGreaterThan($expected), $message);
    }

    /**
     * Asserts that a value is greater than or equal to another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertGreaterThanOrEqual($expected, $actual, string $message = ''): void
    {
        assertThat($actual, isGreaterThanOrEqualTo($expected), $message);
    }

    /**
     * Asserts that a value is smaller than another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertLessThan($expected, $actual, string $message = ''): void
    {
        assertThat($actual, isLessThan($expected), $message);
    }

    /**
     * Asserts that a value is smaller than or equal to another value.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertLessThanOrEqual($expected, $actual, string $message = ''): void
    {
        assertThat($actual, isLessThanOrEqualTo($expected), $message);
    }

    /**
     * Asserts that a file exists.
     *
     * @param string $filename
     * @param string $message
     */
    public static function assertFileExists(string $filename, string $message = ''): void
    {
        assertThat($filename, isExistingFile()->or(isExistingDirectory()), $message);
    }

    /**
     * Asserts that a file does not exist.
     *
     * @param string $filename
     * @param string $message
     */
    public static function assertFileNotExists(string $filename, string $message = ''): void
    {
        assertThat($filename, isNonExistingFile()->and(isNonExistingDirectory()), $message);
    }

    /**
     * Asserts that a condition is true.
     *
     * @param bool   $condition
     * @param string $message
     */
    public static function assertTrue($condition, string $message = ''): void
    {
        assertTrue($condition, $message);
    }

    /**
     * Asserts that a condition is false.
     *
     * @param bool   $condition
     * @param string $message
     */
    public static function assertFalse($condition, string $message = ''): void
    {
        assertFalse($condition, $message);
    }

    /**
     * Asserts that a variable is not null.
     *
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertNotNull($actual, string $message = ''): void
    {
        assertNotNull($actual, $message);
    }

    /**
     * Asserts that a variable is null.
     *
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertNull($actual, string $message = ''): void
    {
        assertNull($actual, $message);
    }

    /**
     * Asserts that a variable is finite.
     *
     * @param  mixed   $actual
     * @param  string  $message
     * @since  1.1.0
     */
    public static function assertFinite($actual, string $message = ''): void
    {
        assertThat($actual, 'is_finite', $message);
    }

    /**
     * Asserts that a variable is infinite.
     *
     * @param  mixed   $actual
     * @param  string  $message
     * @since  1.1.0
     */
    public static function assertInfinite($actual, string $message = ''): void
    {
        assertThat($actual, 'is_infinite', $message);
    }

    /**
     * Asserts that a variable is nan.
     *
     * @param  mixed   $actual
     * @param  string  $message
     * @since  1.1.0
     */
    public static function assertNan($actual, string $message = ''): void
    {
        assertThat($actual, 'is_nan', $message);
    }

    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference
     * the same object.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertSame($expected, $actual, string $message = ''): void
    {
        assertThat($actual, isSameAs($expected), $message);
    }

    /**
     * Asserts that two variables do not have the same type and value.
     * Used on objects, it asserts that two variables do not reference
     * the same object.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertNotSame($expected, $actual, string $message = ''): void
    {
        assertThat($actual, isNotSameAs($expected), $message);
    }

    /**
     * Asserts that a variable is of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertInstanceOf(string $expected, $actual, string $message = ''): void
    {
        assertThat($actual, isInstanceOf($expected), $message);
    }

    /**
     * Asserts that a variable is not of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertNotInstanceOf(string $expected, $actual, string $message = ''): void
    {
        assertThat($actual, isNotInstanceOf($expected), $message);
    }

    /**
     * Asserts that a variable is of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertInternalType(string $expected, $actual, string $message = ''): void
    {
        assertThat($actual, isOfType($expected), $message);
    }

    /**
     * Asserts that a variable is not of a given type.
     *
     * @param string $expected
     * @param mixed  $actual
     * @param string $message
     */
    public static function assertNotInternalType(string $expected, $actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType($expected), $message);
    }

    /**
     * Asserts that a string matches a given regular expression.
     *
     * @param string $pattern
     * @param string $string
     * @param string $message
     */
    public static function assertRegExp(string $pattern, string $string, string $message = ''): void
    {
        assertThat($string, matches($pattern), $message);
    }

    /**
     * Asserts that a string does not match a given regular expression.
     *
     * @param string $pattern
     * @param string $string
     * @param string $message
     */
    public static function assertNotRegExp(string $pattern, string $string, string $message = ''): void
    {
        assertThat($string, doesNotMatch($pattern), $message);
    }

    /**
     * Asserts that a string matches a given format string.
     *
     * @param string $format
     * @param string $string
     * @param string $message
     */
    public static function assertStringMatchesFormat(string $format, string $string, string $message = ''): void
    {
        assertThat($string, matchesFormat($format), $message);
    }

    /**
     * Asserts that a string does not match a given format string.
     *
     * @param string $format
     * @param string $string
     * @param string $message
     */
    public static function assertStringNotMatchesFormat(string $format, string $string, string $message = ''): void
    {
        assertThat($string, doesNotMatchFormat($format), $message);
    }

    /**
     * Asserts that a string starts with a given prefix.
     *
     * @param  string  $prefix
     * @param  string  $string
     * @param  string  $message
     * @since  1.1.0
     */
    public static function assertStringStartsWith(string $prefix, string $string, string $message = ''): void
    {
        assertThat($string, startsWith($prefix), $message);
    }

    /**
     * Asserts that a string starts not with a given prefix.
     *
     * @param  string  $prefix
     * @param  string  $string
     * @param  string  $message
     * @since  1.1.0
     */
    public static function assertStringStartsNotWith($prefix, $string, string $message = ''): void
    {
        assertThat($string, doesNotStartWith($prefix), $message);
    }

    /**
     * Asserts that a string ends with a given suffix.
     *
     * @param  string  $suffix
     * @param  string  $string
     * @param  string  $message
     * @since  1.1.0
     */
    public static function assertStringEndsWith(string $suffix, string $string, string $message = ''): void
    {
        assertThat($string, endsWith($suffix), $message);
    }

    /**
     * Asserts that a string ends not with a given suffix.
     *
     * @param  string  $suffix
     * @param  string  $string
     * @param  string  $message
     * @since  1.1.0
     */
    public static function assertStringEndsNotWith(string $suffix, string $string, string $message = ''): void
    {
        assertThat($string, doesNotEndWith($suffix), $message);
    }

    /**
     * Asserts that a variable is of type array.
     *
     * @since  5.0.0
     */
    public static function assertIsArray($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('array'), $message);
    }

    /**
     * Asserts that a variable is of type bool.
     *
     * @since  5.0.0
     */
    public static function assertIsBool($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('bool'), $message);
    }

    /**
     * Asserts that a variable is of type float.
     *
     * @since  5.0.0
     */
    public static function assertIsFloat($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('float'), $message);
    }

    /**
     * Asserts that a variable is of type int.
     *
     * @since  5.0.0
     */
    public static function assertIsInt($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('int'), $message);
    }

    /**
     * Asserts that a variable is of type numeric.
     *
     * @since  5.0.0
     */
    public static function assertIsNumeric($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('numeric'), $message);
    }

    /**
     * Asserts that a variable is of type object.
     *
     * @since  5.0.0
     */
    public static function assertIsObject($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('object'), $message);
    }

    /**
     * Asserts that a variable is of type resource.
     *
     * @since  5.0.0
     */
    public static function assertIsResource($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('resource'), $message);
    }

    /**
     * Asserts that a variable is of type string.
     *
     * @since  5.0.0
     */
    public static function assertIsString($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('string'), $message);
    }

    /**
     * Asserts that a variable is of type scalar.
     *
     * @since  5.0.0
     */
    public static function assertIsScalar($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('scalar'), $message);
    }

    /**
     * Asserts that a variable is of type callable.
     *
     * @since  5.0.0
     */
    public static function assertIsCallable($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('callable'), $message);
    }

    /**
     * Asserts that a variable is of type iterable.
     *
     * @since  5.0.0
     */
    public static function assertIsIterable($actual, string $message = ''): void
    {
        assertThat($actual, isOfType('iterable'), $message);
    }

    /**
     * Asserts that a variable is not of type array.
     *
     * @since  5.0.0
     */
    public static function assertIsNotArray($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('array'), $message);
    }

    /**
     * Asserts that a variable is not of type bool.
     *
     * @since  5.0.0
     */
    public static function assertIsNotBool($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('bool'), $message);
    }

    /**
     * Asserts that a variable is not of type float.
     *
     * @since  5.0.0
     */
    public static function assertIsNotFloat($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('float'), $message);
    }

    /**
     * Asserts that a variable is not of type int.
     *
     * @since  5.0.0
     */
    public static function assertIsNotInt($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('int'), $message);
    }

    /**
     * Asserts that a variable is not of type numeric.
     *
     * @since  5.0.0
     */
    public static function assertIsNotNumeric($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('numeric'), $message);
    }

    /**
     * Asserts that a variable is not of type object.
     *
     * @since  5.0.0
     */
    public static function assertIsNotObject($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('object'), $message);
    }

    /**
     * Asserts that a variable is not of type resource.
     *
     * @since  5.0.0
     */
    public static function assertIsNotResource($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('resource'), $message);
    }

    /**
     * Asserts that a variable is not of type string.
     *
     * @since  5.0.0
     */
    public static function assertIsNotString($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('string'), $message);
    }

    /**
     * Asserts that a variable is not of type scalar.
     *
     * @since  5.0.0
     */
    public static function assertIsNotScalar($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('scalar'), $message);
    }

    /**
     * Asserts that a variable is not of type callable.
     *
     * @since  5.0.0
     */
    public static function assertIsNotCallable($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('callable'), $message);
    }

    /**
     * Asserts that a variable is not of type iterable.
     *
     * @since  5.0.0
     */
    public static function assertIsNotIterable($actual, string $message = ''): void
    {
        assertThat($actual, isNotOfType('iterable'), $message);
    }

    /**
     * Evaluates a PHPUnit\Framework\Constraint matcher object.
     *
     * @param  mixed                         $value
     * @param  PHPUnit\Framework\Constraint  $constraint
     * @param  string                        $message
     */
    public static function assertThat($value, Constraint $constraint, string $message = ''): void
    {
        assertThat($value, new ConstraintAdapter($constraint), $message);
    }
}
