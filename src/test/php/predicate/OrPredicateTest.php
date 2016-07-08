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

use function bovigo\assert\assert;
use function bovigo\assert\expect;
/**
 * Test for bovigo\assert\predicate\OrPredicate.
 *
 * @group  predicate
 */
class OrPredicateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  OrPredicate
     */
    private $orPredicate;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->orPredicate = new OrPredicate(
                function($value) { return 'bar' === $value; },
                function($value) { return 'foo' === $value; }
        );
    }

    /**
     * @test
     */
    public function returnsTrueWhenOnePredicateReturnsTrue()
    {
        assert($this->orPredicate->test('foo'), isTrue());
    }

    /**
     * @test
     */
    public function returnsFalseWhenBothPredicatesReturnsFalse()
    {
        assert($this->orPredicate->test('baz'), isFalse());
    }

    /**
     * @test
     */
    public function returnsTrueWhenFirstPredicateThrowsExceptionButOtherSucceeds()
    {
        assert(
                assert(null, matches('/^([a-z]{3})$/')->or(isNull())),
                isTrue()
        );
    }

    /**
     * @test
     */
    public function doesNotSwallowExceptionFromFirstPredicateIfOtherFails()
    {
        expect(function() {
                assert(303, matches('/^([a-z]{3})$/')->or(isNull()));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that 303 matches regular expression "/^([a-z]{3})$/" or is null.
Given value of type "integer" can not be matched against a regular expression.'
        );
    }

    /**
     * @test
     */
    public function doesNotSwallowExceptionFromSecondPredicateIfFirstFails()
    {
        expect(function() {
                assert(303, isNull()->or(matches('/^([a-z]{3})$/')));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that 303 is null or matches regular expression "/^([a-z]{3})$/".
Given value of type "integer" can not be matched against a regular expression.'
        );
    }

    /**
     * @test
     */
    public function doesNotSwallowBothExceptionsWhenBothPredicatesFail()
    {
        expect(function() {
                assert(303, matches('/^([a-z]{3})$/')->or(contains('dummy')));
        })
        ->throws(AssertionFailure::class)
        ->withMessage(
                'Failed asserting that 303 matches regular expression "/^([a-z]{3})$/" or contains \'dummy\'.
Given value of type "integer" can not be matched against a regular expression.
Given value of type "integer" can not contain something.'
        );
    }

    /**
     * @test
     */
    public function hasStringRepresentation()
    {
        assert(
                $this->orPredicate,
                equals('satisfies a lambda function or satisfies a lambda function')
        );
    }

    /**
     * @test
     */
    public function countEqualsSumOfCountOfBothPredicates()
    {
        assert(
                count(new OrPredicate(
                        new AndPredicate(
                            function($value) { return 'foo' === $value; },
                            function($value) { return 'foo' === $value; }
                        ),
                        function($value) { return 'bar' === $value; }
                )),
                equals(3)
        );
    }
}
