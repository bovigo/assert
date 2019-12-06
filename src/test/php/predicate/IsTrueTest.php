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

use function bovigo\assert\assertThat;
use function bovigo\assert\assertTrue;
use function bovigo\assert\expect;
/**
 * Tests for bovigo\assert\predicate\IsTrue.
 *
 * @group  predicate
 */
class IsTrueTest extends TestCase
{
    /**
     * @test
     */
    public function evaluatesToTrueIfGivenValueIsTrue(): void
    {
        assertThat(isTrue()->test(true), isSameAs(true));
    }

    /**
     * @return  array<string,array<mixed>>
     */
    public function falseValues(): array
    {
        return [
          'boolean false'    => [false],
          'non-empty string' => ['foo'],
          'empty string'     => [''],
          'empty array'      => [[]],
          'non-empty array'  => [[1]]
        ];
    }

    /**
     * @param  mixed  $false
     * @test
     * @dataProvider  falseValues
     */
    public function evaluatesToFalseIfGivenValueIsFalse($false): void
    {
        assertThat(isTrue()->test($false), isSameAs(false));
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation(): void
    {
        expect(function() { assertTrue([]); })
                ->throws(AssertionFailure::class)
                ->withMessage("Failed asserting that an array is true.");
    }
}
