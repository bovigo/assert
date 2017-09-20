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
 * Tests for bovigo\assert\predicate\IsFalse.
 *
 * @group  predicate
 */
class IsFalseTest extends TestCase
{
    /**
     * @test
     */
    public function evaluatesToTrueIfGivenValueIsFalse()
    {
        assert(isFalse()->test(false), isSameAs(true));
    }

    /**
     * @return  array
     */
    public function trueValues(): array
    {
        return [
          'boolean true'     => [true],
          'non-empty string' => ['foo'],
          'empty string'     => [''],
          'empty array'      => [[]],
          'non-empty array'  => [[1]]
        ];
    }

    /**
     * @param  mixed  $true
     * @test
     * @dataProvider  trueValues
     */
    public function evaluatesToFalseIfGivenValueIsFalse($true)
    {
        assert(isFalse()->test($true), isSameAs(false));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        expect(function() { assert(1, isFalse()); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that 1 is false.");
    }
}
