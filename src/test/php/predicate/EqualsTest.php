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
 * Tests for bovigo\assert\predicate\Equals.
 *
 * @group  predicate
 */
class EqualsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return  array
     */
    public function tuplesEvaluatingToTrue()
    {
        return [[true, true],
                [false, false],
                [5, 5],
                [null, null],
                ['foo', 'foo'],
                [true, 5],
                [false, 0],
                [false, null]
        ];
    }

    /**
     * @param  scalar  $expected
     * @param  mixed   $value
     * @test
     * @dataProvider  tuplesEvaluatingToTrue
     */
    public function evaluatesToTrue($expected, $value)
    {
        assert(equals($expected)->test($value), isTrue());
    }

    /**
     * @return  array
     */
    public function tuplesEvaluatingToFalse()
    {
        return [[true, false],
                [false, true],
                [false, new \stdClass()],
                [5, 'foo'],
                [5, 6],
                [true, 'foo'],
                ['foo', 'bar'],
                [5, new \stdClass()],
                ['foo', new \stdClass()]
        ];
    }

    /**
     * @param  scalar  $expected
     * @param  mixed   $value
     * @test
     * @dataProvider  tuplesEvaluatingToFalse
     */
    public function evaluatesToFalse($expected, $value)
    {
        assert(equals($expected)->test($value), isFalse());
    }

    /**
     * @test
     */
    public function assertionFailureContainsMeaningfulInformation()
    {
        try {
            assert('bar', equals('foo'), 'additional info');
        } catch (AssertionFailure $af) {
            assert(
                    $af->getMessage(),
                    equals("Failed asserting that 'bar' is equal to <string:foo>.
--- Expected
+++ Actual
@@ @@
-'foo'
+'bar'

additional info"
                    )
            );
        }
    }
}
