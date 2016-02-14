<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate\exception;
/**
 * Predicate which checks that an exception is of an expected type.
 *
 * @since  1.6.0
 */
class Type extends ExceptionPredicate
{
    /**
     * @type  string
     */
    private $expectedType;

    /**
     * constructor
     *
     * @param  string  $expectedType
     */
    public function __construct($expectedType)
    {
        $this->expectedType = $expectedType;
    }

    /**
     * actual test
     *
     * @param   \Exception  $ex
     * @return  bool
     */
    public function testException(\Exception $ex)
    {
        return $ex instanceof $this->expectedType;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return 'matches expected exception "' . $this->expectedType . '"';
    }

    /**
     * create description for exception
     *
     * @param   \Exception  $ex
     * @return  string
     */
    public function describeException(\Exception $ex)
    {
        return 'exception of type ' . get_class($ex);
    }
}