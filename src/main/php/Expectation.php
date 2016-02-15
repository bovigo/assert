<?php
/**
 * This file is part of bovigo\callmap.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use bovigo\assert\predicate\Predicate;
use bovigo\assert\predicate\ExpectedException;
/**
 * An expectation executes some code after which assertions can be made.
 *
 * @since  1.6.0
 */
class Expectation
{
    /**
     * @type  callable
     */
    private $code;
    /**
     * marker on whether code was already executed
     *
     * @type  bool
     */
    private $executed = false;
    /**
     * @type  mixed
     */
    private $result;
    /**
     * @type  \Exception
     */
    private $exception;

    /**
     * constructor
     *
     * @param  callable  $code
     */
    public function __construct(callable $code)
    {
        $this->code = $code;
    }

    /**
     * runs code and returns result
     *
     * @return  mixed
     */
    private function runCode()
    {
        if ($this->executed) {
            return $this->result;
        }

        $code = $this->code;
        try {
            $this->result = $code();
        } catch (\Exception $ex) {
            $this->exception = $ex;
        } finally {
            $this->executed = true;
        }

        return $this->result;
    }

    /**
     * asserts the code throws an exception when executed
     *
     * If no expected type is given any thrown exception will be sufficient.
     *
     * @api
     * @param   string  $expectedType  optional  type of exception
     * @return  \bovigo\assert\CatchedException
     * @throws  \bovigo\assert\AssertionFailure
     */
    public function throws($expectedType = null)
    {
        $this->runCode();
        if (null === $this->exception) {
            throw new AssertionFailure(
                    'Failed asserting that '
                    . (null !== $expectedType
                        ? 'exception of type "' . $expectedType . '"'
                        : 'an exception'
                    )
                    . ' is thrown.'
            );
        } elseif (null !== $expectedType) {
            assert($this->exception, new ExpectedException($expectedType));
        }

        return new CatchedException($this->exception);
    }

    /**
     * asserts that code does not throw an exception when executed
     *
     * If no expected type is given the code is not allowed to throw any
     * exception.
     *
     * @api
     * @param   string  $unexpectedType  optional  type of exception which should not be thrown
     * @return  \bovigo\assert\Expectation
     * @throws  \bovigo\assert\AssertionFailure
     */
    public function doesNotThrow($unexpectedType = null)
    {
        $this->runCode();
        if (null !== $this->exception
                && (null === $unexpectedType || $this->exception instanceof $unexpectedType)) {
            throw new AssertionFailure(
                    'Failed asserting that no exception'
                    . (null !== $unexpectedType
                        ? ' of type "' . $unexpectedType . '"'
                        : ''
                    )
                    . ' is thrown, got ' . get_class($this->exception)
                    . ' with message "' . $this->exception->getMessage() . '".'
            );
        }

        return $this;
    }

    /**
     * asserts result of executed code fulfills a predicate
     *
     * @param   \bovigo\assert\predicate\Predicate|callable  $predicate    predicate or callable to test given value
     * @param   string                                       $description  optional  additional description for failure message
     * @return  \bovigo\assert\Expectation
     */
    public function result($predicate, $description = null)
    {
        $this->runCode();
        if (null !== $this->exception) {
            throw new AssertionFailure(
                    'Failed asserting that result '
                    . Predicate::castFrom($predicate)
                    . ' because exception of type '
                    . get_class($this->exception)
                    . ' with message "' . $this->exception->getMessage()
                    . '" was thrown.'
            );
        }

        assert($this->result, $predicate, $description);
        return $this;
    }

    /**
     * asserts anything after the code was executed, even if it threw an exception
     *
     * @api
     * @param   mixed                                        $value        value to test
     * @param   \bovigo\assert\predicate\Predicate|callable  $predicate    predicate or callable to test given value
     * @param   string                                       $description  optional  additional description for failure message
     * @return  \bovigo\assert\Expectation
     */
    public function after($value, $predicate, $description = null)
    {
        $this->runCode();
        assert($value, $predicate, $description);
        return $this;
    }
}
