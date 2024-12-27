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
use bovigo\assert\predicate\Wrap;
use Throwable;

use function bovigo\assert\predicate\equals;
/**
 * Allows to make assertions on a catched exception.
 *
 * @since  1.6.0
 */
class CatchedException
{
    public function __construct(private Throwable $actualException) { }

    /**
     * asserts actual exception equals expected message
     *
     * @api
     */
    public function withMessage(string $expectedMessage): self
    {
        return $this->message(equals($expectedMessage));
    }

    /**
     * asserts actual exception fulfills given predicate
     *
     * @api
     */
    public function message(Predicate|callable $predicate): self
    {
        assertThat(
                $this->actualException->getMessage(),
                new Wrap(Predicate::castFrom($predicate), 'exception message %s')
        );
        return $this;
    }

    /**
     * asserts actual exception code equals expected code
     *
     * @api
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
     */
    public function with(Predicate|callable $predicate, ?string $description = null): self
    {
        assertThat($this->actualException, $predicate, $description);
        return $this;
    }

    /**
     * asserts anything after the exception was catched
     *
     * @api
     */
    public function after(mixed $value, Predicate|callable $predicate, ?string $description = null): self
    {
        assertThat($value, $predicate, $description);
        return $this;
    }
}
