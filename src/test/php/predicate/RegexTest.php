<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert\AssertionFailure;
use PHPUnit\Framework\TestCase;

use function bovigo\assert\assert;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\Regex.
 *
 * @group  predicate
 */
class RegexTest extends TestCase
{
    /**
     * @return  array
     */
    public function validValues(): array
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
    public function invalidValues(): array
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
     */
    public function nonStringsThrowInvalidArgumentException()
    {
        expect(function() { matches('/^([a-z]{3})$/')->test(303); })
                ->throws(\InvalidArgumentException::class)
                ->withMessage(
                        'Given value of type "integer" can not be matched against a regular expression.'
        );
    }

    /**
     * @test
     */
    public function nullThrowInvalidArgumentException()
    {
        expect(function() { matches('/^([a-z]{3})$/')->test(null); })
                ->throws(\InvalidArgumentException::class)
                ->withMessage(
                        'Given value of type "NULL" can not be matched against a regular expression.'
        );
    }

    /**
     * @test
     */
    public function invalidRegexThrowsRuntimeExceptionOnEvaluation()
    {
        expect(function() {
            $regex = new Regex('^([a-z]{3})$');
            $regex('foo');
        })
        ->throws(\RuntimeException::class)
        ->withMessage(
                'Failure while matching "^([a-z]{3})$", reason: invalid regular expression.'
        );
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
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assert('f', matches('/^([a-z]{3})$/')); })
                ->throws(AssertionFailure::class)
                ->withMessage(
                        "Failed asserting that 'f' matches regular expression \"/^([a-z]{3})$/\"."
        );
    }
}
