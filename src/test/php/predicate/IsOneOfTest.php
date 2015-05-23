<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Tests for bovigo\assert\predicate\IsOneOf.
 *
 * @group  predicate
 */
class IsOneOfTest extends \PHPUnit_Framework_TestCase
{
    /**
     * instance to test
     *
     * @type  IsOneOf
     */
    private $isOneOf;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->isOneOf = new IsOneOf(['foo', 'bar']);
    }

    /**
     * @return  array
     */
    public function validValues()
    {
        return [['foo'],
                ['bar'],
                [['bar', 'foo']]
        ];
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  validValues
     */
    public function validValueEvaluatesToTrue($value)
    {
        assertTrue($this->isOneOf->test($value));
    }

    /**
     * @return  array
     */
    public function invalidValues()
    {
        return [['baz'],
                [null],
                [['bar', 'foo', 'baz']]
        ];
    }

    /**
     * @param  string  $value
     * @test
     * @dataProvider  invalidValues
     */
    public function invalidValueEvaluatesToFalse($value)
    {
        assertFalse($this->isOneOf->test($value));
    }
}
