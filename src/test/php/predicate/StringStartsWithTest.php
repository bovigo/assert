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
 * Tests for bovigo\assert\predicate\StringStartsWith.
 *
 * @group  predicate
 * @since  1.1.0
 */
class StringStartsWithTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function nonStringValuesThrowInvalidArgumentException()
    {
        expect(function() { startsWith('foo')->test(303); })
                ->throws(\InvalidArgumentException::class);
    }

    /**
     * @return  array
     */
    public function trueValues(): array
    {
        return [
          'string which starts with and contains foo' => ['foobarfoobaz'],
          'string which starts with foo'              => ['foobarbaz']
        ];
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  trueValues
     */
    public function evaluatesToTrueIfStringStartsWithPrefix($value)
    {
        assert(startsWith('foo')->test($value), isTrue());
    }

    /**
     * @return  array
     */
    public function falseValues(): array
    {
        return [
          'string which contains foo'  => ['barfoobaz'],
          'string which ends with foo' => ['barbazfoo']
        ];
    }

    /**
     * @param  mixed  $value
     * @test
     * @dataProvider  falseValues
     */
    public function evaluatesToFalseIfGivenValueIsFalse($value)
    {
        assert(startsWith('foo')->test($value), isFalse());
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assert('bar', startsWith('foo')); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 'bar' starts with 'foo'.");
    }
}
