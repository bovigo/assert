<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\callmap.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use bovigo\assert\predicate\Wrap;

use function bovigo\assert\predicate\equals;
/**
 * Allows to make assertions on a catched exception.
 *
 * @since  1.6.0
 */
class CatchedException
{
    /**
     * @type  \Exception
     */
    private $actualException;

    /**
     * constructor
     *
     * @param  \Throwable  $actualException
     */
    public function __construct(\Throwable $actualException)
    {
        $this->actualException = $actualException;
    }

    /**
     * asserts actual exception equals expected message
     *
     * @api
     * @param   string  $expectedMessage
     * @return  \bovigo\assert\CatchedException
     */
    public function withMessage(string $expectedMessage): self
    {
        return $this->message(equals($expectedMessage));
    }

    /**
     * asserts actual exception fulfills given predicate
     *
     * @api
     * @param   \bovigo\assert\predicate\Predicate|callable  $predicate
     * @return  \bovigo\assert\CatchedException
     */
    public function message(callable $predicate): self
    {
        assertThat(
                $this->actualException->getMessage(),
                new Wrap($predicate, 'exception message %s')
        );
        return $this;
    }

    /**
     * asserts actual exception code equals expected code
     *
     * @api
     * @param   int  $expectedCode
     * @return  \bovigo\assert\CatchedException
     */
    public function withCode(int $expectedCode): self
    {
        assertThat(
                $this->actualException->getCode(),
                new Wrap(equals($expectedCode), 'exception code %s')
        );
        return $this;
    }

    /**
     * asserts actual exception fulfills predicate
     *
     * @api
     * @param   \bovigo\assert\predicate\Predicate|callable  $predicate
     * @param   string                                       $description  optional  additional description for failure message
     * @return  \bovigo\assert\CatchedException
     */
    public function with(callable $predicate, string $description = null): self
    {
        assertThat($this->actualException, $predicate, $description);
        return $this;
    }

    /**
     * asserts anything after the exception was catched
     *
     * @api
     * @param   mixed                                        $value        value to test
     * @param   \bovigo\assert\predicate\Predicate|callable  $predicate    predicate or callable to test given value
     * @param   string                                       $description  optional  additional description for failure message
     * @return  \bovigo\assert\Expectation
     */
    public function after($value, callable $predicate, string $description = null): self
    {
        assertThat($value, $predicate, $description);
        return $this;
    }
}
