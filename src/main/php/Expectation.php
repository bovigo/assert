<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;

use bovigo\assert\predicate\{Predicate, ExpectedError, ExpectedException};
use InvalidArgumentException;
use Throwable;

use function bovigo\assert\predicate\isSameAs;
/**
 * An expectation executes some code after which assertions can be made.
 *
 * @since  1.6.0
 */
class Expectation
{
    /**
     * @var callable
     */
    private $code;
    /**
     * marker on whether code was already executed
     */
    private bool $executed = false;
    private mixed $result = null;
    private ?Throwable $exception = null;
    private ?TriggeredError $error = null;

    public function __construct(callable $code)
    {
        $this->code = $code;
    }

    private function runCode(): void
    {
        if ($this->executed) {
            return;
        }

        $code = $this->code;
        try {
            $this->result = $code();
        } catch (Throwable $ex) {
            $this->exception = $ex;
        } finally {
            $this->executed = true;
        }
    }

    /**
     * asserts the code throws an exception when executed
     *
     * If no expected is given any thrown exception will be sufficient.
     *
     * @api
     * @throws  AssertionFailure
     */
    public function throws(string|Throwable|null $expected = null): CatchedException
    {
        $this->runCode();
        if (null === $this->exception) {
            throw new AssertionFailure(
                'Failed asserting that '
                . (null !== $expected
                    ? 'exception of type "' . $this->getType($expected) . '"'
                    : 'an exception'
                )
                . ' is thrown.'
            );
        } elseif (null !== $expected) {
            if (is_string($expected)) {
                $isExpected = new ExpectedException($expected);
            } else {
                $isExpected = isSameAs($expected);
            }

            assertThat($this->exception, $isExpected);
        }

        return new CatchedException($this->exception);
    }

    private function getType(string|Throwable $expected): string
    {
        return is_string($expected) ? $expected : get_class($expected);
    }

    /**
     * asserts that code does not throw an exception when executed
     *
     * If no expected type is given the code is not allowed to throw any
     * exception.
     *
     * @api
     * @throws  AssertionFailure
     */
    public function doesNotThrow(?string $unexpectedType = null): self
    {
        increaseAssertionCounter(1);
        $this->runCode();
        if (
            null !== $this->exception
            && (
                null === $unexpectedType
                || $this->exception instanceof $unexpectedType
            )
        ) {
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
     * @throws  InvalidArgumentException  in case the given expected error is unknown
     * @throws  AssertionFailure
     * @since   2.1.0
     */
    public function triggers(?int $expectedError = null): TriggeredError
    {
        if (null !== $expectedError && !TriggeredError::knowsLevel($expectedError)) {
            throw new InvalidArgumentException('Unknown error level ' . $expectedError);
        }

        set_error_handler(
            function(int $errno , string $errstr, string $errfile, int $errline, ?array $errcontext = null): bool
            {
                $this->error = new TriggeredError($errno, $errstr, $errfile, $errline, $errcontext ?? []);
                return true;
            }
        );
        $this->runCode();
        restore_error_handler();
        if (null === $this->error) {
            throw new AssertionFailure(
                'Failed asserting that '
                . (null !== $expectedError
                    ? 'error of type "' . TriggeredError::nameOf($expectedError) . '"'
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
     */
    public function result(Predicate|callable $predicate, ?string $description = null): self
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
     */
    public function after(
        mixed $value,
        Predicate|callable $predicate,
        ?string $description = null
    ): self {
        $this->runCode();
        assertThat($value, $predicate, $description);
        return $this;
    }
}
