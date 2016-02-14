<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate\exception;
use bovigo\assert\predicate\Predicate;
use SebastianBergmann\Exporter\Exporter;
/**
 * Base predicate for tests of exceptions.
 *
 * @since  1.6.0
 */
abstract class ExceptionPredicate extends Predicate
{
    /**
     * tests that the given value contains expected key
     *
     * @param   mixed  $value
     * @return  bool
     * @throws  \InvalidArgumentException  in case given value can't have a key
     */
    public function test($value)
    {
        if (!is_callable($value)) {
            throw new \InvalidArgumentException('Given value is not an exception');
        }

        return $this->testException($value);
    }

    /**
     * actual test
     *
     * @param   \Exception  $ex
     * @return  bool
     */
    public abstract function testException(\Exception $ex);


    /**
     * returns a textual description of given value
     *
     * @param   \SebastianBergmann\Exporter\Exporter  $exporter
     * @param   mixed                                 $value
     * @return  string
     * @throws  \InvalidArgumentException  in case given value can't have a key
     */
    public function describeValue(Exporter $exporter, $value)
    {
        if (!is_callable($value)) {
            throw new \InvalidArgumentException('Given value is not an exception');
        }

        return $this->describeException($value);
    }

    /**
     * create description for exception
     *
     * @param   \Exception  $ex
     * @return  string
     */
    public abstract function describeException(\Exception $ex);
}
