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
 * Helper class for the test.
 */
class CountableExample implements \Countable
{
    public function count()
    {
        return 3;
    }
}
/**
 * Helper class for the test.
 */
class TraversableExample implements \Iterator
{
    private $current = 0;

    public function current()
    {
        return $this->current;
    }

    public function key()
    {
        return $this->current;
    }

    public function next()
    {
        $this->current++;
    }

    public function rewind()
    {
        $this->current = 0;
    }

    public function valid()
    {
        return 3 > $this->current;
    }

}
/**
 * Helper class for the test.
 */
class IteratorAggregateExample implements \IteratorAggregate
{
    public function getIterator()
    {
        return new \ArrayIterator([1, 2, 3]);
    }
}
/**
 * Tests for bovigo\assert\predicate\IsOfSize.
 *
 * @group  predicate
 */
class IsOfSizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function evaluatesToTrueIfStringLengthMatchesExpectedSize()
    {
        assert(isOfSize(3)->test('foo'), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfStringLengthDoesNotMatchExpectedSize()
    {
        assert(isOfSize(4)->test('foo'), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfArraySizeMatchesExpectedSize()
    {
        assert(isOfSize(3)->test([1, 2, 3]), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfArraySizeDoesNotMatchExpectedSize()
    {
        assert(isOfSize(4)->test([1, 2, 3]), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfCountableSizeMatchesExpectedSize()
    {
        assert(isOfSize(3)->test(new CountableExample()), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfCountableSizeDoesNotMatchExpectedSize()
    {
        assert(isOfSize(4)->test(new CountableExample()), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfTraversableSizeMatchesExpectedSize()
    {
        assert(isOfSize(3)->test(new TraversableExample()), isTrue());
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfTraversableSizeDoesNotMatchExpectedSize()
    {
        assert(isOfSize(4)->test(new TraversableExample()), isFalse());
    }

    /**
     * @test
     */
    public function evaluatesToTrueIfIteratorAggregateSizeMatchesExpectedSize()
    {
        assert(
                isOfSize(3)->test(new IteratorAggregateExample()),
                isTrue()
        );
    }

    /**
     * @test
     */
    public function evaluatesToFalseIfIteratorAggregateSizeDoesNotMatchExpectedSize()
    {
        assert(
                isOfSize(4)->test(new IteratorAggregateExample()),
                isFalse()
        );
    }

    /**
     * @test
     */
    public function iteratorPointerIsNotChangedByEvaluationForTraversable()
    {
        $example = new TraversableExample();
        $example->next();
        isOfSize(3)->test($example);
        assert($example->current(), equals(1));
    }

    /**
     * @test
     * @expectedException  InvalidArgumentException
     */
    public function throwsInvalidArgumentExceptionWhenValueIsNotTestableForSize()
    {
        isOfSize(3)->test(true);
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that string with actual size 3 matches expected size 4.
     */
    public function assertionFailureWithStringContainsMeaningfulInformation()
    {
        assert('foo', isOfSize(4));
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that array with actual size 0 matches expected size 4.
     */
    public function assertionFailureWithArrayContainsMeaningfulInformation()
    {
        assert([], isOfSize(4));
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that instance of type bovigo\assert\predicate\CountableExample with actual size 3 matches expected size 4.
     */
    public function assertionFailureWithCountableContainsMeaningfulInformation()
    {
        assert(new CountableExample(), isOfSize(4));
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that instance of type bovigo\assert\predicate\TraversableExample with actual size 3 matches expected size 4.
     */
    public function assertionFailureWithTraversableContainsMeaningfulInformation()
    {
        assert(new TraversableExample(), isOfSize(4));
    }

    /**
     * @test
     * @expectedException  bovigo\assert\AssertionFailure
     * @expectedExceptionMessage  Failed asserting that instance of type bovigo\assert\predicate\IteratorAggregateExample with actual size 3 matches expected size 4.
     */
    public function assertionFailureWithIteratorAggregateContainsMeaningfulInformation()
    {
        assert(new IteratorAggregateExample(), isOfSize(4));
    }
}
