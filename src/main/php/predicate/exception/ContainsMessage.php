<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate\exception;
/**
 * Predicate which checks that an exception message contains an expected message.
 *
 * @since  1.6.0
 */
class ContainsMessage extends ExceptionPredicate
{
    /**
     * @type  string
     */
    private $expectedMessage;

    /**
     * constructor
     *
     * @param  string  $expectedMessage
     */
    public function __construct($expectedMessage)
    {
        $this->expectedMessage = $expectedMessage;
    }

    /**
     * actual test
     *
     * @param   \Exception  $ex
     * @return  bool
     */
    public function testException(\Exception $ex)
    {
        return strpos($ex->getMessage(), $this->expectedMessage) !== false;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return "contains '" . $this->expectedMessage . "'";
    }

    /**
     * create description for exception
     *
     * @param   \Exception  $ex
     * @return  string
     */
    public function describeException(\Exception $ex)
    {
        return "with message '" . $ex->getMessage() . "'";
    }
}