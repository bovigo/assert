<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\callmap.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use bovigo\assert\predicate\Predicate;
use bovigo\assert\predicate\ExpectedError;
use bovigo\assert\predicate\ExpectedException;

use function bovigo\assert\predicate\isSameAs;
/**
 * An expectation executes some code after which assertions can be made.
 *
 * @since  1.6.0
 */
class Expectation
{
    /**
     * @var  callable
     */
    private $code;
    /**
     * marker on whether code was already executed
     *
     * @var  bool
     */
    private $executed = false;
    /**
     * @var  mixed
     */
    private $result;
    /**
     * @var  \Throwable
     */
    private $exception;
    /**
     * @var  CatchedError
     */
    private $error;

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
        } catch (\Throwable $ex) {
            $this->exception = $ex;
        } finally {
            $this->executed = true;
        }

        return $this->result;
    }

    /**
     * asserts the code throws an exception when executed
     *
     * If no expected is given any thrown exception will be sufficient.
     *
     * @api
     * @param   string|\Throwable  $expected  optional  type of exception or the exact exception
     * @return  \bovigo\assert\CatchedException
     * @throws  \bovigo\assert\AssertionFailure
     * @throws  \InvalidArgumentException
     */
    public function throws($expected = null): CatchedException
    {
        $this->runCode();
        if (null === $this->exception) {
            throw new AssertionFailure(
                    'Failed asserting that '
                    . (null !== $expected
                        ? 'exception of type "' . (is_string($expected) ? $expected : get_class($expected)) . '"'
                        : 'an exception'
                    )
                    . ' is thrown.'
            );
        } elseif (null !== $expected) {
            if (is_string($expected)) {
                $isExpected = new ExpectedException($expected);
            } elseif ($expected instanceof \Throwable) {
                $isExpected = isSameAs($expected);
            } else {
                throw new \InvalidArgumentException(
                        'Given expected is neither a class name nor an instance'
                        . ' of \Throwable, but of type ' . gettype($expected)
                );
            }

            assertThat($this->exception, $isExpected);
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
    public function doesNotThrow(string $unexpectedType = null): self
    {
        increaseAssertionCounter(1);
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
     * asserts the code triggers an error when executed
     *
     * If no expected error level is given any triggered error will be sufficient.
     *
     * @api
     * @param   int  $expectedError  optional  error level to expect
     * @return  \bovigo\assert\CatchedError
     * @throws  \InvalidArgumentException        in case the given expected error is unknown
     * @throws  \bovigo\assert\AssertionFailure
     * @since   2.1.0
     */
    public function triggers(int $expectedError = null): CatchedError
    {
        if (null !== $expectedError && !CatchedError::knowsLevel($expectedError)) {
            throw new \InvalidArgumentException('Unknown error level ' . $expectedError);
        }

        set_error_handler(
                function(int $errno , string $errstr, string $errfile, int $errline, array $errcontext): bool
                {
                    $this->error = new CatchedError($errno, $errstr, $errfile, $errline, $errcontext);
                    return true;
                }
        );
        $this->runCode();
        restore_error_handler();
        if (null === $this->error) {
            throw new AssertionFailure(
                    'Failed asserting that '
                    . (null !== $expectedError
                        ? 'error of type "' . CatchedError::nameOf($expectedError) . '"'
                        : 'an error'
                    )
                    . ' is triggered.'
            );
        } elseif (null !== $expectedError) {
            assertThat($this->error, new ExpectedError($expectedError));
        }

        return $this->error;
    }

    /**
     * asserts result of executed code fulfills a predicate
     *
     * @param   \bovigo\assert\predicate\Predicate|callable  $predicate    predicate or callable to test given value
     * @param   string                                       $description  optional  additional description for failure message
     * @return  \bovigo\assert\Expectation
     */
    public function result(callable $predicate, string $description = null): self
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

        assertThat($this->result, $predicate, $description);
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
    public function after($value, callable $predicate, string $description = null): self
    {
        $this->runCode();
        assertThat($value, $predicate, $description);
        return $this;
    }
}
