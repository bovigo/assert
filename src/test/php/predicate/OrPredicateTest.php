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
                assert(null, matches('/^([a-z]{3})$/')->orElse(isNull())),
                isTrue()
        );
    }

    /**
     * @test
     */
    public function doesNotSwallowExceptionFromFirstPredicateIfOtherFails()
    {
        try {
            assert(303, matches('/^([a-z]{3})$/')->orElse(isNull()));
            $this->fail('Expected ' . AssertionFailure::class . ', got none');
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that 303 matches regular expression "/^([a-z]{3})$/" or is null.
Given value of type "integer" can not be matched against a regular expression.')
            );
        }
    }

    /**
     * @test
     */
    public function doesNotSwallowExceptionFromSecondPredicateIfFirstFails()
    {
        try {
            assert(303, isNull()->orElse(matches('/^([a-z]{3})$/')));
            $this->fail('Expected ' . AssertionFailure::class . ', got none');
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that 303 is null or matches regular expression "/^([a-z]{3})$/".
Given value of type "integer" can not be matched against a regular expression.')
            );
        }
    }

    /**
     * @test
     */
    public function doesNotSwallowBothExceptionsWhenBothPredicatesFail()
    {
        try {
            assert(303, matches('/^([a-z]{3})$/')->orElse(contains('dummy')));
            $this->fail('Expected ' . AssertionFailure::class . ', got none');
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals('Failed asserting that 303 matches regular expression "/^([a-z]{3})$/" or contains \'dummy\'.
Given value of type "integer" can not be matched against a regular expression.
Given value of type "integer" can not contain something.')
            );
        }
    }

    /**
     * @test
     */
    public function hasStringRepresentation()
    {
        assert(
                $this->orPredicate,
                equals('callable<lambda> or callable<lambda>')
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
