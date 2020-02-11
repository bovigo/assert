bovigo/assert
==============

Provides assertions for unit tests.


Package status
--------------

[![Build Status](https://secure.travis-ci.org/bovigo/assert.png)](http://travis-ci.org/bovigo/assert) [![Build Status Windows](https://ci.appveyor.com/api/projects/status/t1u7ylbvdkbqqxyv?svg=true)](https://ci.appveyor.com/project/mikey179/assert) [![Coverage Status](https://coveralls.io/repos/github/bovigo/assert/badge.svg?branch=master)](https://coveralls.io/github/bovigo/assert?branch=master)

[![Latest Stable Version](https://poser.pugx.org/bovigo/assert/version.png)](https://packagist.org/packages/bovigo/assert) [![Latest Unstable Version](https://poser.pugx.org/bovigo/assert/v/unstable.png)](//packagist.org/packages/bovigo/assert)


Installation
------------

_bovigo/assert_ is distributed as [Composer](https://getcomposer.org/) package.
To install it as a development dependency of your package use the following
command:

    composer require --dev "bovigo/assert": "^6.0"

To install it as a runtime dependency for your package use the following command:

    composer require "bovigo/assert=^6.0"


Requirements
------------

_bovigo/assert_ 6.x requires at least PHP 7.3.


Why?
----

The original idea was to explore how a more functional approach to using
assertions in unit tests could look like, and if it would make for a better
reading of test code. Personally, I found the results convincing enough that I
wanted to use it in my own code, so I made a package of it.


Usage
-----

All assertions are written in the same way using functions:

```php
assertThat(303, equals(303));
assertThat($someArray, isOfSize(3), 'array always must have size 3');
```

The first parameter is the value to test, and the second is the predicate that
should be used to test the value. Additionally, an optional description can be
supplied to enhance clarity in case the assertion fails.

In case the predicate fails an `AssertionFailure` will be thrown with useful
information of why the test failed. In case PHPUnit is used `AssertionFailure`
is an instance of `\PHPUnit\Framework\AssertionFailedError` so it
integrates nicely into PHPUnit, yielding a similar test output as PHPUnit's
constraints. Here is an example of the output in case of a test failure:

```
1) bovigo\assert\predicate\RegexTest::stringRepresentationContainsRegex
Failed asserting that 'matches regular expression "/^([a-z]{3})$/"' is equal to <string:matches regular expession "/^([a-z]{3})$/">.
--- Expected
+++ Actual
@@ @@
-'matches regular expession "/^([a-z]{3})$/"'
+'matches regular expression "/^([a-z]{3})$/"'

bovigo-assert/src/test/php/predicate/RegexTest.php:99
```

For the sake of brevity below it is assumed the used functions are imported into
the current namespace via
```php
use function bovigo\assert\assertThat;
use function bovigo\assert\predicate\isOfSize;
use function bovigo\assert\predicate\equals;
// ... and so on
```


List of predicates
------------------

This is the list of predicates that are included in _bovigo/assert_ by default.


### `isNull()`

Tests if value is `null`.

```php
assertThat($value, isNull());
```

Alias: `bovigo\assert\assertNull($value, $description = null)`


### `isNotNull()`

Tests that value is not `null`.

```php
assertThat($value, isNotNull());
```

Alias: `bovigo\assert\assertNotNull($value, $description = null)`


### `isEmpty()`

Tests that value is empty. Empty is defined as follows:

* In case the value is an instance of `\Countable` it is empty when its count
  is 0.
* For all other values the rules for PHP's `empty()` apply.

```php
assertThat($value, isEmpty());
```

Aliases:
* `bovigo\assert\assertEmpty($value, $description = null)`
* `bovigo\assert\assertEmptyString($value, $description = null)`
* `bovigo\assert\assertEmptyArray($value, $description = null)`


### `isNotEmpty()`

Tests that value is not empty. See `isEmpty()` for definition of emptyness.

```php
assertThat($value, isNotEmpty());
```

Alias: `bovigo\assert\assertNotEmpty($value, $description = null)`


### `isTrue()`

Tests that a value is true. The value must be boolean true, no value conversion
is applied.

```php
assertThat($value, isTrue());
```

Alias: `bovigo\assert\assertTrue($value, $description = null)`


### `isFalse()`

Tests that a value is false. The value must be boolean false, no value
conversion is applied.

```php
assertThat($value, isFalse());
```

Alias: `bovigo\assert\assertFalse($value, $description = null)`


### `equals($expected)`

Tests that a value equals the expected value. The optional parameter `$delta`
can be used when equality of float values should be tested and allows for a
certain range in which two floats are considered equal.

```php
assertThat($value, equals('Roland TB 303'));
```

In case a delta is needed, e.g. for float values, the required delta can be set:

```php
assertThat($value, equals(5)->withDelta(0.1));
```

### `isNotEqualTo($unexpected)`

Tests that a value is not equal to the unexpected value. The optional parameter
`$delta` can be used when equality of float values should be tested and allows
for a certain range in which two floats are considered equal.

```php
assertThat($value, isNotEqualTo('Roland TB 303'));
```

In case a delta is needed, e.g. for float values, the required delta can be set:

```php
assertThat($value, isNotEqualTo(5)->withDelta(0.1));
```


### `isInstanceOf($expectedType)`

Tests that a value is an instance of the expected type.

```php
assertThat($value, isInstanceOf(\stdClass::class));
```


### `isNotInstanceOf($unexpectedType)`

Tests that a value is not an instance of the unexpected type.

```php
assertThat($value, isNotInstanceOf(\stdClass::class));
```


### `isSameAs($expected)`

Tests that a value is identical to the expected value. Both values are compared
with `===`, the according rules apply.

```php
assertThat($value, isSameAs($anotherValue));
```


### `isNotSameAs($unexpected)`

Tests that a value is not identical to the unexpected value. Both values are
compared with `===`, the according rules apply.

```php
assertThat($value, isNotSameAs($anotherValue));
```


### `isOfSize($expectedSize)`

Tests that a value has the expected size. The rules for the size are as follows:

* For strings, their length in bytes is used.
* For array and instances of `\Countable` the value of `count()` is used.
* For instances of `\Traversable` the value of `iterator_count()` is used. To
  prevent moving the pointer of the traversable, `iterator_count()` is applied
  against a clone of the traversable.
* All other value types will be rejected.

```php
assertThat($value, isOfSize(3));
```


### `isNotOfSize($unexpectedSize)`

Tests that a value does not have the unexpected size. The rules are the same as
for `isOfSize($expectedSize)`.

```php
assertThat($value, isNotOfSize(3));
```


### `isOfType($expectedType)`

Tests that a value is of the expected internal PHP type.

```php
assertThat($value, isOfType('resource'));
```

#### Aliases

Since release 5.0 some alias functions are provided to prevent typos in usages of that function:

* `bovigo\assert\predicate\isArray()`
* `bovigo\assert\predicate\isBool()`
* `bovigo\assert\predicate\isFloat()`
* `bovigo\assert\predicate\isInt()`
* `bovigo\assert\predicate\isNumeric()`
* `bovigo\assert\predicate\isObject()`
* `bovigo\assert\predicate\isResource()`
* `bovigo\assert\predicate\isString()`
* `bovigo\assert\predicate\isScalar()`
* `bovigo\assert\predicate\isCallable()`
* `bovigo\assert\predicate\isIterable()`


### `isNotOfType($unexpectedType)`

Tests that a value is not of the unexpected internal PHP type.

```php
assertThat($value, isNotOfType('resource'));
```

#### Aliases

Since release 5.0 some alias functions are provided to prevent typos in usages of that function. Please note that some are specific to ensure the code you write with them forms a grammatically valid sentence.

* `bovigo\assert\predicate\isNotAnArray()`
* `bovigo\assert\predicate\isNotBool()`
* `bovigo\assert\predicate\isNotFloat()`
* `bovigo\assert\predicate\isNotInt()`
* `bovigo\assert\predicate\isNotNumeric()`
* `bovigo\assert\predicate\isNotAnObject()`
* `bovigo\assert\predicate\isNotAResource()`
* `bovigo\assert\predicate\isNotAString()`
* `bovigo\assert\predicate\isNotScalar()`
* `bovigo\assert\predicate\isNotCallable()`
* `bovigo\assert\predicate\isNotIterable()`


### `isGreaterThan($expected)`

Tests that a value is greater than the expected value.

```php
assertThat($value, isGreaterThan(3));
```


### `isGreaterThanOrEqualTo($expected)`

Tests that a value is greater than or equal to the expected value.

```php
assertThat($value, isGreaterThanOrEqualTo(3));
```


### `isLessThan($expected)`

Tests that a value is less than the expected value.

```php
assertThat($value, isLessThan(3));
```


### `isLessThanOrEqualTo($expected)`

Tests that a value is less than or equal to the expected value.

```php
assertThat($value, isLessThanOrEqualTo(3));
```


### `contains($needle)`

Tests that `$needle` is contained in value. The following rules apply:

* `null` is contained in `null`.
* A string can be contained in another string. The comparison is case sensitive.
* `$needle` can be a value of an array or a `\Traversable`. Value and `$needle`
  are compared with `===`.
* For all other cases, the value is rejected.

```php
assertThat($value, contains('Roland TB 303'));
```

Sometimes it is necessary to differentiate between arrays, Traversables and strings.
If a particular type should be enforced it is recommended to combine predicates:
```php
assertThat($value, isArray()->and(contains('Roland TB 303')));
assertThat($value, isString()->and(contains('Roland TB 303')));
assertThat($value, isInstanceOf(\Iterator::class)->and(contains('Roland TB 303')));
```


### `doesNotContain($needle)`

Tests that `$needle` is not contained in value. The rules of `contains($needle)`
apply.

```php
assertThat($value, doesNotContain('Roland TB 303'));
```


### `hasKey($key)`

Tests that an array or an instance of `\ArrayAccess` have a key with given name.
The key must be either of type `integer` or `string`. Values that are neither an
array nor an instance of `\ArrayAccess` are rejected.

```php
assertThat($value, hasKey('roland'));
```


### `doesNotHaveKey($key)`

Tests that an array or an instance of `\ArrayAccess` does not have a key with
given name. The key must be either of type `integer` or `string`. Values that
are neither an array nor an instance of `\ArrayAccess` are rejected.

```php
assertThat($value, doesNotHaveKey('roland'));
```


### `matches($pattern)`

Tests that a string matches the given pattern of a regular expression. If the
value is not a string it is rejected. The test is successful if the pattern
yields at least one match in the value.

```php
assertThat($value, matches('/^([a-z]{3})$/'));
```


### `doesNotMatch($pattern)`

Tests that a string does not match the given pattern of a regular expression. If
the value is not a string it is rejected. The test is successful if the pattern
yields no match in the value.

```php
assertThat($value, doesNotMatch('/^([a-z]{3})$/'));
```


### `matchesFormat($format)`
_Available since release 3.2.0._

Tests that a string matches the given PHP format expression. If the value is not
a string it is rejected. The test is successful if the format yields at least
one match in the value. The format string may contain the following placeholders:

* `%e`: Represents a directory separator, for example / on Linux.
* `%s`: One or more of anything (character or white space) except the end of line character.
* `%S`: Zero or more of anything (character or white space) except the end of line character.
* `%a`: One or more of anything (character or white space) including the end of line character.
* `%A`: Zero or more of anything (character or white space) including the end of line character.
* `%w`: Zero or more white space characters.
* `%i`: A signed integer value, for example +3142, -3142.
* `%d`: An unsigned integer value, for example 123456.
* `%x`: One or more hexadecimal character. That is, characters in the range 0-9, a-f, A-F.
* `%f`: A floating point number, for example: 3.142, -3.142, 3.142E-10, 3.142e+10.
* `%c`: A single character of any sort.

```php
assertThat($value, matchesFormat('%w'));
```


### `doesNotMatchFormat($format)`
_Available since release 3.2.0._

Tests that a string does not match the given PHP format expression. If the value
is not a string it is rejected. The test is successful if the pattern yields no
match in the value. See above for a list of possible formats.

```php
assertThat($value, doesNotMatchFormat('%w'));
```


### `isExistingFile($basePath = null)`

Tests that the value denotes an existing file. If no `$basepath` is supplied the
value must either be an absolute path or a relative path to the current working
directory. When `$basepath` is given the value must be a relative path to this
basepath.

```php
assertThat($value, isExistingFile());
assertThat($value, isExistingFile('/path/to/files'));
```


### `isNonExistingFile($basePath = null)`

Tests that the value denotes a file which does not exist. If no `$basepath` is
supplied the value must either be an absolute path or a relative path to the
current working directory. When `$basepath` is given the value must be a
relative path to this basepath.

```php
assertThat($value, isNonExistingFile());
assertThat($value, isNonExistingFile('/path/to/files'));
```


### `isExistingDirectory($basePath = null)`

Tests that the value denotes an existing directory. If no `$basepath` is
supplied the value must either be an absolute path or a relative path to the
current working directory. When `$basepath` is given the value must be a
relative path to this basepath.

```php
assertThat($value, isExistingDirectory());
assertThat($value, isExistingDirectory('/path/to/directories'));
```


### `isNonExistingDirectory($basePath = null)`

Tests that the value denotes a non-existing directory. If no `$basepath` is
supplied the value must either be an absolute path or a relative path to the
current working directory. When `$basepath` is given the value must be a
relative path to this basepath.

```php
assertThat($value, isNonExistingDirectory());
assertThat($value, isNonExistingDirectory('/path/to/directories'));
```


### `startsWith($prefix)`
_Available since release 1.1.0._

Tests that the value which must be a string starts with given prefix.

```php
assertThat($value, startsWith('foo'));
```


### `doesNotStartWith($prefix)`
_Available since release 1.1.0._

Tests that the value which must be a string does not start with given prefix.

```php
assertThat($value, startsWith('foo'));
```


### `endsWith($suffix)`
_Available since release 1.1.0._

Tests that the value which must be a string ends with given suffix.

```php
assertThat($value, endsWith('foo'));
```


### `doesNotEndWith($suffix)`
_Available since release 1.1.0._

Tests that the value which must be a string does not end with given suffix.

```php
assertThat($value, doesNotEndWith('foo'));
```


### `each($predicate)`
_Available since release 1.1.0._

Applies a predicate to each value of an array or traversable.

```php
assertThat($value, each(isInstanceOf($expectedType));
```

Please note that an empty array or traversable will result in a successful test.
If it must not be empty use `isNotEmpty()->and(each($predicate))`:

```php
assertThat($value, isNotEmpty()->and(each(isInstanceOf($expectedType))));
```

It can also be used with any callable:

```php
assertThat($value, each('is_nan'));
assertThat($value, each(function($value) { return substr($value, 4, 3) === 'foo'; }));
```

### `eachKey($predicate)`
_Available since release 1.3.0._

Applies a predicate to each key of an array or traversable.

```php
assertThat($value, eachKey(isOfType('int'));
```

Please note that an empty array or traversable will result in a successful test.
If it must not be empty use `isNotEmpty()->and(eachKey($predicate))`:

```php
assertThat($value, isNotEmpty()->and(eachKey(isOfType('int'))));
```

It can also be used with any callable:

```php
assertThat($value, eachKey('is_int'));
assertThat($value, eachKey(function($value) { return substr($value, 4, 3) === 'foo'; }));
```


### `not($predicate)`

Reverses the meaning of a predicate.

```php
assertThat($value, not(isTrue()));
```

It can also be used with any callable:

```php
assertThat($value, not('is_nan'));
assertThat($value, not(function($value) { return substr($value, 4, 3) === 'foo'; }));
```


Combining predicates
--------------------

Each predicate provides both two methods to combine this predicate with another
predicate into a new predicate.

### `and($predicate)`

Creates a predicate where both combined predicate must be `true` so that the
combined predicate is `true` as well. If one of the predicates fails, the
combined predicate will fail as well.

```php
assertThat($value, isNotEmpty()->and(eachKey(isOfType('int'))));
```

It can also be used with any callable:

```php
assertThat($value, isNotEmpty()->and('is_string'));
```


### `or($predicate)`

Creates a predicate where one of the combined predicates must be `true`. Only if
all predicates fail the combined predicate will fail as well.

```php
assertThat($value, equals(5)->or(isLessThan(5)));
```

It can also be used with any callable:

```php
assertThat($value, isNull()->or('is_finite'));
```


User defined predicates
-----------------------

To define a predicate to be used in an assertion there are two possibilities:

### Use a callable

You can pass anything that is a `callable` to the `assertThat()` function:
```php
assertThat($value, 'is_nan');
```
This will create a predicate which uses PHP's builtin `is_nan()` function to
test the value.

The callable should accept a single value (the value to test, obviously) and
must return `true` on success and `false` on failure. It is also allowed to
throw any exception.

Here is an example with a closure:
```php
assertThat(
        $value,
        function($value)
        {
            if (!is_string($value)) {
                throw new \InvalidArgumentException(
                        'Given value is not a string.'
                );
            }

            return substr($value, 4, 3) === 'foo';
        }
);
```


### Extend `bovigo\assert\predicate\Predicate`

The other possibility is to extend the `bovigo\assert\predicate\Predicate` class.
You need to implement at least the following methods:

#### `public function test($value)`

This method receives the value to test and should return `true` on success and
`false` on failure. It is also allowed to throw any exception.

#### `public function __toString()`

This method must return a proper description of the predicate which fits into
the sentences shown when an asssertion fails. These sentences are composed as
follows:

_Failed asserting that [description of value] [description of predicate]._

Additionally, the predicate can influence _[description of value]_ by overriding
the `describeValue(Exporter $exporter, $value)` method.


Instant failure
---------------
_Available since release 1.2.0._

In case assertions are not enough and the test needs to fail when it reaches a
certain point, `bovigo\assert\fail($description)` can be used to trigger an
instant assertion failure:

```php
try {
    somethingThatThrowsFooException();
    fail('Expected ' . FooException::class . ', gone none');
} catch (FooException $fo) {
    // some assertions on FooException
}
```


phpstan and early terminating function calls
--------------------------------------------

_Available since release 5.1.0_

In case you are using [phpstan](https://github.com/phpstan/phpstan) _bovigo/assert_
provides a config file you can include in your phpstan config so early terminating
function calls with `fail()` are recognized.

```
includes:
  - vendor/bovigo/assert/src/main/resources/phpstan/bovigo-assert.neon
```


Expectations
------------
_Available since release 1.6.0_

Expectations can be used to check that a specific piece of code does or does not
throw an exception or trigger an error. It can also be used to check that after
a specific piece of code ran assertions are still true, despite of whether the
code in question succeeded or not.


### Expectations on exceptions

Note: since release 2.1.0 it is also possible to use expectations with `\Error`.

Check that a piece of code, e.g. a function or method, throws an exception:
```php
expect(function() {
    // some piece of code which is expected to throw SomeException
})->throws(SomeException::class);
```

It is also possible to expect any exception, not just a specific one, by leaving
out the class name of the exception:
```php
expect(function() {
    // some piece of code which is expected to throw any exception
})->throws();
```

Since release 2.1.0 it is possible to verify that exactly a given exception was
thrown:
```php
$exception = new \Exception('failure');
expect(function() use ($exception) {
    throw $exception;
})->throws($exception);
```
This will perform an assertion with `isSameAs($exception)` for the thrown
exception.

Additionally checks on the thrown exception can be performed:
```php
expect(function() {
    // some piece of code which is expected to throw SomeException
})
->throws(SomeException::class)
->withMessage('some failure occured');
```

The following checks on the exception are possible:
  * `withMessage(string $expectedMessage)`
    Performs an assertion with `equals()` on the exception message.
  * `message($predicate)`
    Performs an assertion with the given predicate on the exception message.
  * `withCode(int $expectedCode)`
    Performs an assertion with `equals()` on the exception code.
  * `with($predicate)`
    Performs an assertion on the whole exception with given predicate. The
    predicate will receive the exception as argument and can perform any check.

```php
expect(function() {
    // some piece of code which is expected to throw SomeException
})
->throws(SomeException::class)
->with(
        function(SomeException $e) { return null !== $e->getPrevious(); },
        'exception does have a previous exception'
);
```


Of course you can also check that a specific exception did not occur:
```php
expect(function() {
    // some piece of code which is expected to not throw SomeException
})->doesNotThrow(SomeException::class);
```

By leaving out the exception name you ensure that the code doesn't throw any
exception at all:
```php
expect(function() {
    // some piece of code which is expected to not throw any exception
})->doesNotThrow();
```

In case any of these expectations fail an `AssertionFailure` will be thrown.


### Expectations on errors

_Available since release 2.1.0_

Check that a piece of code, e.g. a function or method, triggers an error:
```php
expect(function() {
    // some piece of code which is expected to trigger an error
})->triggers(E_USER_ERROR);
```

It is also possible to expect any error, not just a specific one, by leaving
out the error level:
```php
expect(function() {
    // some piece of code which is expected to trigger an error
})->triggers();
```

Additionally checks on the triggered error can be performed:
```php
expect(function() {
    // some piece of code which is expected to trigger an error
})
->triggers(E_USER_WARNING)
->withMessage('some error occured');
```

The following checks on the exception are possible:
  * `withMessage(string $expectedMessage)`
    Performs an assertion with `equals()` on the error message.
  * `message($predicate)`
    Performs an assertion with the given predicate on the error message.

In case any of these expectations fail an `AssertionFailure` will be thrown.


### Expectations on state after a piece of code was executed

Sometimes it may be useful to assert that a certain state exists after some
piece of code is executed, regardless of whether this execution succeeds.
```php
expect(function() {
    // some piece of code here
})
->after(SomeClass::$value, equals(303));
```

It is possible to combine this with expectations on whether an exception is
thrown or not:
```php
expect(function() {
    // some piece of code here
})
->doesNotThrow()
->after(SomeClass::$value, equals(303));

expect(function() {
    // some piece of code here
})
->throws(SomeException::class)
->after(SomeClass::$value, equals(303));
```


Verify output of a function or method
-------------------------------------
_Available since release 2.1.0_

When a function or method utilizes `echo` it can be cumbersome to check if it
prints the correct output. For this, the `outputOf()` function was introduced:

```php
outputOf(
        function() { echo 'Hello you!'; },
        equals('Hello world!')
);
```

The first parameter is a callable which prints some output, the second is any
predicate which will than be applied to the output. `outputOf()` takes care of
enabling and disabling output buffering to catch the output.


PHPUnit compatibility layer
---------------------------

In case you want to check out how _bovigo/assert_ works with your tests there is
a PHPUnit compatibility layer available. Instead of extending directly from
`\PHPUnit\Framework\TestCase` let your tests extend
`bovigo\assert\phpunit\TestCase`. It overlays all constraints
from PHPUnit with predicates from _bovigo/assert_  where they are available.
For constraints which have no equivalent predicate in _bovigo/assert_ the
default constraints from PHPUnit are used.


FAQ
---

### How can I access a property of a class or object for the assertions?

Unlike PHPUnit _bovigo/assert_  does not provide means to assert that a property
of a class fullfills a certain constraint. If the property is public you can
pass it directly into the `assertThat()` function as a value. In any other case
_bovigo/assert_ does not support accessing protected or private properties.
There's a reason why they are protected or private, and a test should only be
against the public API of a class, not against their inner workings.
