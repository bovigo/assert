<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use bovigo\assert\predicate\exception\ContainsMessage;
use bovigo\assert\predicate\exception\ExceptionPredicate;
use bovigo\assert\predicate\exception\HasCode;
use bovigo\assert\predicate\exception\Type;
use SebastianBergmann\Exporter\Exporter;
/**
 * Predicate to check that a piece of code throws an exception.
 *
 * @since  1.6.0
 */
class ExpectedException extends Predicate
{
    /**
     * @type  \bovigo\assert\predicate\exception\ExceptionPredicate[]
     */
    private $exceptionPredicates = [];
    /**
     *
     * @type  string|\bovigo\assert\predicate\exception\ExceptionPredicate
     */
    private $failed;

    /**
     * constructor
     *
     * @param  string  $expectedType  optional  type of exception to be thrown
     */
    public function __construct($expectedType = null)
    {
        if (null !== $expectedType) {
            $this->with(new Type($expectedType));
            $this->failed = 'exception of type "' . $expectedType . '" is thrown';
        } else {
            $this->failed = 'an exception is thrown.';
        }
    }

    /**
     * sets expected exception message
     *
     * @api
     * @param   string  $expectedMessage
     * @return  \bovigo\assert\predicate\ExpectedException
     */
    public function withMessage($expectedMessage)
    {
        return $this->with(new ContainsMessage($expectedMessage));
    }

    /**
     * sets expected exception code
     *
     * @api
     * @param   int  $expectedCode
     * @return  \bovigo\assert\predicate\ExpectedException
     */
    public function withCode($expectedCode)
    {
        return $this->with(new HasCode($expectedCode));
    }

    /**
     * sets another expectation with given predicate
     *
     * @api
     * @param   ExceptionPredicate  $predicate
     * @return  \bovigo\assert\predicate\ExpectedException
     */
    public function with(ExceptionPredicate $predicate)
    {
        $this->exceptionPredicates[] = $predicate;
        return $this;
    }

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
            throw new \InvalidArgumentException('Given value is not a callable');
        }

        try {
            $value();
        } catch (\Exception $ex) {
            foreach ($this->exceptionPredicates as $exceptionPredicate) {
                if (!$exceptionPredicate->testException($ex)) {
                    $this->failed = $exceptionPredicate;
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return (string) $this->failed;
    }

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
            throw new \InvalidArgumentException('Given value is not a callable');
        }

        try {
            $value();
            return '';
        } catch (\Exception $ex) {
            $descriptions = [];
            foreach ($this->exceptionPredicates as $exceptionPredicate) {
                $descriptions[] = $exceptionPredicate->describeException($ex);
                if ($exceptionPredicate === $this->failed) {
                    break;
                }
            }

            return join(' ', $descriptions);
        }
    }
}
