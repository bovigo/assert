<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Tests for bovigo\assert\predicate\Regex.
 *
 * @group  predicate
 */
class RegexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return  array
     */
    public function validValues()
    {
        return [['/^([a-z]{3})$/', 'foo'],
                ['/^([a-z]{3})$/i', 'foo'],
                ['/^([a-z]{3})$/i', 'Bar']
        ];
    }

    /**
     * @param  string  $regex
     * @param  string  $value
     * @test
     * @dataProvider  validValues
     */
    public function validValueEvaluatesToTrue($regex, $value)
    {
        $validate = new Regex($regex);
        assertTrue($validate($value));
    }

    /**
     * @return  array
     */
    public function invalidValues()
    {
        return [['/^([a-z]{3})$/', 'Bar'],
                ['/^([a-z]{3})$/', 'baz0123'],
                ['/^([a-z]{3})$/', null],
                ['/^([a-z]{3})$/i', 'baz0123'],
                ['/^([a-z]{3})$/i', null]
        ];
    }

    /**
     * @param  string  $regex
     * @param  string  $value
     * @test
     * @dataProvider  invalidValues
     */
    public function invalidValueEvaluatesToFalse($regex, $value)
    {
        $validate = new Regex($regex);
        assertFalse($validate($value));
    }

    /**
     * @test
     * @expectedException  RuntimeException
     */
    public function invalidRegexThrowsRuntimeExceptionOnEvaluation()
    {
        $regex = new Regex('^([a-z]{3})$');
        $regex('foo');
    }
}
