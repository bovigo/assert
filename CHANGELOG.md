5.0.0 (2019-??-??)
------------------

### BC breaks

  * Removed deprecated `bovigo\assert\assert()`
  * Raised minimum required PHP version to 7.2.0

### Other changes
  * `bovigo\assert\predicate\isOfType()` can now check for iterable types
  * Added support in compatibility layer for specialized alternatives to `assertInternalType()` and `assertNotInternalType()` introduced with PHPUnit 7.5
  * Ensured compatibility with PHPUnit 8.0
  * Added new shortcut functions for specific `bovigo\assert\predicate\isOfType()` and `bovigo\assert\predicate\isNotOfType()` uses:
    * `bovigo\assert\predicate\isArray()`
    * `bovigo\assert\predicate\isNotAnArray()`
    * `bovigo\assert\predicate\isBool()`
    * `bovigo\assert\predicate\isNotBool()`
    * `bovigo\assert\predicate\isFloat()`
    * `bovigo\assert\predicate\isNotFloat()`
    * `bovigo\assert\predicate\isInt()`
    * `bovigo\assert\predicate\isNotInt()`
    * `bovigo\assert\predicate\isNumeric()`
    * `bovigo\assert\predicate\isNotNumeric()`
    * `bovigo\assert\predicate\isObject()`
    * `bovigo\assert\predicate\isNotAnObject()`
    * `bovigo\assert\predicate\isResource()`
    * `bovigo\assert\predicate\isNotAResource()`
    * `bovigo\assert\predicate\isString()`
    * `bovigo\assert\predicate\isNotAString()`
    * `bovigo\assert\predicate\isScalar()`
    * `bovigo\assert\predicate\isNotScalar()`
    * `bovigo\assert\predicate\isCallable()`
    * `bovigo\assert\predicate\isNotCallable()`
    * `bovigo\assert\predicate\isIterable()`
    * `bovigo\assert\predicate\isNotIterable()`


4.0.0 (2018-02-11)
------------------

### BC breaks

  * updated dependencies to be compatible with PHPUnit 7.x


3.2.0 (2017-12-28)
------------------

  * Implemented #8: add support for assertStringMatchesFormat
    * Added new functions `bovigo\assert\predicate\matchesFormat()` and `bovigo\assert\predicate\doesNotMatchFormat()`


3.1.0 (2017-10-30)
------------------

  * Fixed #7: `bovigo\assert\assert()` not executed when zend.assertions not set to 1
    * Added new function `bovigo\assert\assertThat()`, made `bovigo\assert\assert()` an alias for this
    * Deprecated `bovigo\assert\assert()`


3.0.0 (2017-09-20)
------------------

### BC breaks

  * raised minimum required PHP version to 7.1.0
  * updated dependencies to be compatible with PHPUnit 6.x


2.2.0 (2017-09-20)
------------------

  * updated sebastian/exporter to 2.0 to ensure compatibility with PHPUnit 5.7


2.1.0 (2016-08-23)
------------------

  * implemented #3 add support for testing output by adding `bovigo\assert\outputOf()`
  * implemented #4 `bovigo\assert\expect()` should work with `\Error`
  * implemented #5 `bovigo\assert\expect()` should provide possibility to test `trigger_error()`
  * allowed to use `bovigo\assert\expect()->throws()` with an instance of `\Throwable`, will assert thrown exception is identical instead of asserting correct type only


2.0.0 (2016-07-10)
------------------

### BC breaks

  * raised minimum required PHP version to 7.0.0
  * introduced scalar type hints and strict type checking
  * removed `bovigo\assert\predicate\Predicate::asWellAs()`, use `bovigo\assert\predicate\Predicate::and()` instead, deprecated since 1.4.0
  * removed `bovigo\assert\predicate\Predicate::orElse()`, use `bovigo\assert\predicate\Predicate::or()` instead, deprecated since 1.4.0
  * removed `bovigo\assert\predicate\Predicate::negate()`, use `bovigo\assert\predicate\not()` instead, deprecated since 1.4.0


1.7.1 (2016-06-28)
------------------

  * implemented #2: each() should specify which exact value fails


1.7.0 (2016-06-24)
------------------

  * added `bovigo\assert\predicate\Equals::hasDiffForLastFailure()`
  * added `bovigo\assert\predicate\Equals::diffForLastFailure()`


1.6.1 (2016-06-20)
------------------

  * implemented #1: improve error message when catched exception does not matched expected exception


1.6.0 (2016-02-21)
------------------

  * added `bovigo\assert\expect()`


1.5.0 (2016-01-11)
------------------

  * added alias `bovigo\assert\assertEmptyString()`
  * added alias `bovigo\assert\assertEmptyArray()`


1.4.0 (2015-12-31)
------------------

### BC breaks

  * deprecated `bovigo\assert\predicate\Predicate::asWellAs()` in favor of `bovigo\assert\predicate\Predicate::and()`, will be removed with 2.0.0
  * deprecated `bovigo\assert\predicate\Predicate::orElse()` in favor of `bovigo\assert\predicate\Predicate::or()`, will be removed with 2.0.0
  * deprecated `bovigo\assert\predicate\Predicate::negate()` in favor of `bovigo\assert\predicate\not()`, will be removed with 2.0.0


### Other changes

  * added `bovigo\assert\predicate\Predicate::and()`
  * added `bovigo\assert\predicate\Predicate::or()`


1.3.0 (2015-12-30)
------------------

  * added `bovigo\assert\predicate\eachKey()`
  * added alias `bovigo\assert\assertTrue()`
  * added alias `bovigo\assert\assertFalse()`
  * added alias `bovigo\assert\assertNull()`
  * added alias `bovigo\assert\assertNotNull()`
  * added alias `bovigo\assert\assertEmpty()`
  * added alias `bovigo\assert\assertNotEmpty()`
  * both `bovigo\assert\predicate\each()` and `bovigo\assert\predicate\isOfSize()` can now work with non-cloneable traversables


1.2.0 (2015-12-28)
------------------

  * added `bovigo\assert\fail()`


1.1.0 (2015-12-28)
------------------

  * added `bovigo\assert\predicate\each()`
  * added `bovigo\assert\phpunit\PHPUnit_Framework_TestCase::assertContainsOnly()`
  * added `bovigo\assert\phpunit\PHPUnit_Framework_TestCase::assertContainsOnlyInstancesOf()`
  * added `bovigo\assert\phpunit\PHPUnit_Framework_TestCase::assertNotContainsOnly()`
  * added `bovigo\assert\predicate\startsWith()`
  * added `bovigo\assert\phpunit\PHPUnit_Framework_TestCase::assertStringStartsWith()`
  * added `bovigo\assert\predicate\doesNotStartWith()`
  * added `bovigo\assert\phpunit\PHPUnit_Framework_TestCase::assertStringStartsNotWith()`
  * added `bovigo\assert\predicate\endsWith()`
  * added `bovigo\assert\phpunit\PHPUnit_Framework_TestCase::assertStringEndsWith()`
  * added `bovigo\assert\predicate\doesNotEndWith()`
  * added `bovigo\assert\phpunit\PHPUnit_Framework_TestCase::assertStringEndsNotWith()`
  * added `bovigo\assert\phpunit\PHPUnit_Framework_TestCase::assertNan()`
  * added `bovigo\assert\phpunit\PHPUnit_Framework_TestCase::assertFinite()`
  * added `bovigo\assert\phpunit\PHPUnit_Framework_TestCase::assertInfinite()`
  * `bovigo\assert\predicate\not()` now also accepts callables


1.0.0 (2015-12-27)
------------------

  * Initial release.
