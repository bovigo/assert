2.0.0 (2016-??-??)
------------------

### BC breaks

  * raised minimum required PHP version to 7.0.0
  * introduced scalar type hints and strict type checking


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
