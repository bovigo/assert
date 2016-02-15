<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert\AssertionFailure;

use function bovigo\assert\assert;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\Contains.
 *
 * @group  predicate
 */
class ContainsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * returns tuples which evaluate to true
     *
     * @return  array
     */
    public function tuplesEvaluatingToTrue()
    {
        return [
                [null, null],
                [5, 'foo5'],
                [5, 'fo5o'],
                ['foo', 'foobar'],
                ['foo', 'foo'],
                ['foo', ['foo', 'bar', 'baz']],
                [null, ['foo', null, 'baz']]
        ];
    }

    /**
     * @param  mixed                      $needle
     * @param  string|array|\Traversable  $haystack
     * @test
     * @dataProvider  tuplesEvaluatingToTrue
     */
    public function evaluatesToTrue($needle, $haystack)
    {
        assert(contains($needle)->test($haystack), isTrue());
    }

    /**
     * returns tuples which evaluate to false
     *
     * @return  array
     */
    public function tuplesEvaluatingToFalse()
    {
        return [
                [5, 'foo'],
                [true, 'blub'],
                ['dummy', 'bar'],
                ['nope', ['foo', 'bar', 'baz']]
        ];
    }

    /**
     * @param  mixed                      $needle
     * @param  string|array|\Traversable  $haystack
     * @test
     * @dataProvider  tuplesEvaluatingToFalse
     */
    public function evaluatesToFalse($needle, $haystack)
    {
        assert(contains($needle)->test($haystack), isFalse());
    }

    /**
     * @test
     */
    public function throwsInvalidArgumentExceptionWhenValueCanNotContainAnything()
    {
        expect(function() { contains('foo')->test(303); })
                ->throws(\InvalidArgumentException::class)
                ->withMessage('Given value of type "integer" can not contain something.');
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assert([], contains('foo')); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that an array contains 'foo'.");
    }
}
