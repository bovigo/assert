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

use function bovigo\assert\predicate\equals;
/**
 * Allows to make assertions on a catched error.
 *
 * @since  2.1.0
 */
class CatchedError
{
    /**
     * map of error level numbers and their textual name
     */
    private const array LEVEL = [
                E_ERROR             => 'E_ERROR',
                E_WARNING           => 'E_WARNING',
                E_PARSE             => 'E_PARSE',
                E_NOTICE            => 'E_NOTICE',
                E_CORE_ERROR        => 'E_CORE_ERROR',
                E_CORE_WARNING      => 'E_CORE_WARNING',
                E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
                E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
                E_USER_ERROR        => 'E_USER_ERROR',
                E_USER_WARNING      => 'E_USER_WARNING',
                E_USER_NOTICE       => 'E_USER_NOTICE',
                E_STRICT            => 'E_STRICT',
                E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
                E_DEPRECATED        => 'E_DEPRECATED',
                E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
                E_ALL               => 'E_ALL'
    ];

    /**
     * checks whether a given error level value is known
     *
     * @since   3.0.0
     */
    public static function knowsLevel(int $level): bool
    {
      return isset(CatchedError::LEVEL[$level]);
    }

    /**
     * returns the name of the integer error level
     */
    public static function nameOf(int $level): string
    {
        return self::LEVEL[$level] ?? 'Unknown error ' . $level;
    }

    /**
     * @param  int           $errno
     * @param  string        $errstr
     * @param  string        $errfile
     * @param  int           $errline
     * @param  array<mixed>  $errcontext
     */
    public function __construct(
        private int $errno,
        private string $errstr,
        private string $errfile,
        private int $errline,
        private array $errcontext = []
    ) { }

    /**
     * returns the error level of the catched error
     */
    public function level(): int
    {
        return $this->errno;
    }

    /**
     * returns the error level name of the catched error
     */
    public function name(): string
    {
        return self::nameOf($this->errno);
    }

    /**
     * returns the actual error strin, i.e. the error message
     */
    public function errstr(): string
    {
        return $this->errstr;
    }

    /**
     * asserts actual error message equals expected message
     *
     * @api
     */
    public function withMessage(string $expectedMessage): self
    {
        return $this->message(equals($expectedMessage));
    }

    /**
     * asserts actual error message fulfills given predicate
     *
     * @api
     */
    public function message(Predicate|callable $predicate): self
    {
        assertThat($this->errstr, new Wrap(Predicate::castFrom($predicate), 'error message %s'));
        return $this;
    }

    /**
     * returns file in which the error was triggered
     */
    public function file(): string
    {
        return $this->errfile;
    }

    /**
     * returns the line on which the error was triggered
     */
    public function line(): int
    {
        return $this->errline;
    }

    /**
     * asserts anything after the error was catched
     *
     * @api
     */
    public function after(mixed $value, Predicate|callable $predicate, string $description = null): self
    {
        assertThat($value, $predicate, $description);
        return $this;
    }
}
