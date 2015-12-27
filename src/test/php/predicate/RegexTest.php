<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use function bovigo\assert\assert;
/**
 * Tests for bovigo\assert\predicate\Regex.
 *
 * @group  predicate
 */
class RegexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return  array
     */
    public function validValues()
    {
        return [['/^([a-z]{3})$/', 'foo'],
                ['/^([a-z]{3})$/i', 'foo'],
                ['/^([a-z]{3})$/i', 'Bar']
        ];
    }

    /**
     * @param  string  $pattern
     * @param  string  $value
     * @test
     * @dataProvider  validValues
     */
    public function validValueEvaluatesToTrue($pattern, $value)
    {
        assert(matches($pattern)->test($value), isTrue());
    }

    /**
     * @return  array
     */
    public function invalidValues()
    {
        return [['/^([a-z]{3})$/', 'Bar'],
                ['/^([a-z]{3})$/', 'baz0123'],
                ['/^([a-z]{3})$/i', 'baz0123']
        ];
    }

    /**
     * @param  string  $pattern
     * @param  string  $value
     * @test
     * @dataProvider  invalidValues
     */
    public function invalidValueEvaluatesToFalse($pattern, $value)
    {
        assert(matches($pattern)->test($value), isFalse());
    }

    /**
     * @test
     * @expectedException  InvalidArgumentException
     * @expectedExceptionMessage  Given value of type "integer" can not be matched against a regular expression
     */
    public function nonStringsThrowInvalidArgumentException()
    {
        matches('/^([a-z]{3})$/')->test(303);
    }

    /**
     * @test
     * @expectedException  InvalidArgumentException
     * @expectedExceptionMessage  Given value of type "NULL" can not be matched against a regular expression
     */
    public function nullThrowInvalidArgumentException()
    {
        matches('/^([a-z]{3})$/')->test(null);
    }

    /**
     * @test
     * @expectedException  RuntimeException
     * @expectedExceptionMessage  Failure while matching "^([a-z]{3})$", reason: invalid regular expression.
     */
    public function invalidRegexThrowsRuntimeExceptionOnEvaluation()
    {
        $regex = new Regex('^([a-z]{3})$');
        $regex('foo');
    }

    /**
     * @test
     */
    public function stringRepresentationContainsRegex()
    {
        assert(
                (string) new Regex('/^([a-z]{3})$/'),
                equals('matches regular expression "/^([a-z]{3})$/"')
        );
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that 'f' matches regular expression "/^([a-z]{3})$/".
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        assert('f', matches('/^([a-z]{3})$/'));
    }
}
