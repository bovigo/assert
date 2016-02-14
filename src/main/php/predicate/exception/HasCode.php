<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate\exception;
/**
 * Predicate which checks that an exception code equals an expected code.
 *
 * @since  1.6.0
 */
class HasCode extends ExceptionPredicate
{
    /**
     * @type  string
     */
    private $expectedCode;

    /**
     * constructor
     *
     * @param  string  $expectedCode
     */
    public function __construct($expectedCode)
    {
        $this->expectedCode = $expectedCode;
    }

    /**
     * actual test
     *
     * @param   \Exception  $ex
     * @return  bool
     */
    public function testException(\Exception $ex)
    {
        return $ex->getCode() === $this->expectedCode;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return "has code '" . $this->expectedCode . "'";
    }

    /**
     * create description for exception
     *
     * @param   \Exception  $ex
     * @return  string
     */
    public function describeException(\Exception $ex)
    {
        return "with code '" . $ex->getCode() . "'";
    }
}