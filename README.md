bovigo/assert
==============

Provides assertions for unit tests. Compatible with any [unit test framework](http://en.wikipedia.org/wiki/List_of_unit_testing_frameworks#PHP).

Package status
--------------

[![Build Status](https://secure.travis-ci.org/mikey179/bovigo-assert.png)](http://travis-ci.org/mikey179/bovigo-assert) [![Coverage Status](https://coveralls.io/repos/mikey179/bovigo-assert/badge.png?branch=master)](https://coveralls.io/r/mikey179/bovigo-assert?branch=master)

[![Latest Stable Version](https://poser.pugx.org/bovigo/assert/version.png)](https://packagist.org/packages/bovigo/assert) [![Latest Unstable Version](https://poser.pugx.org/bovigo/assert/v/unstable.png)](//packagist.org/packages/bovigo/assert)


Installation
------------

_bovigo/assert_ is distributed as [Composer](https://getcomposer.org/) package.
To install it as a development dependency of your package use the following
command:

    composer require --dev "bovigo/assert": "^1.0"

To install it as a runtime dependency for your package use the following command:

    composer require "bovigo/assert=^1.0"

Requirements
------------

_bovigo/assert_ requires at least PHP 5.4.


Usage
-----

Explore the [tests](https://github.com/mikey179/bovigo-callmap/tree/master/src/test/php)
to see how _bovigo/callmap_ can be used. For the very eager, here's a code
example which features almost all of the possibilities:

```php
// set up the instance to be used
$yourClass = NewInstance::of('name\of\YourClass', ['some', 'arguments'])
        ->mapCalls(
                ['aMethod'     => 313,
                 'otherMethod' => function() { return 'yeah'; },
                 'play'        => onConsecutiveCalls(303, 808, 909, throws(new \Exception('error')),
                 'ups'         => throws(new \Exception('error')),
                 'hey'         => 'strtoupper'
                ]
        );

// do some stuff, e.g. execute the logic to test
...

// verify method invocations and received arguments
verify($yourClass, 'aMethod')->wasCalledOnce();
verify($yourClass, 'hey')->received('foo');
```

However, if you prefer text instead of code, read on.

Note: for the sake of brevity below it is assumed the used classes and functions
are imported into the current namespace via
```php
use bovigo\callmap\NewInstance;
use function bovigo\callmap\throws;
use function bovigo\callmap\onConsecutiveCalls;
use function bovigo\callmap\verify;
```

_For PHP versions older than 5.6.0, you can do `use bovigo\callmap` and call them
with `callmap\throws()`, `callmap\onConsecutiveCalls()`, and `callmap\verify()`._

