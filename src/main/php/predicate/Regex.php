<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to ensure a value complies to a given regular expression.
 *
 * The predicate uses preg_match() and checks if the value occurs one or more
 * times. Please make sure that the supplied regular expression contains correct
 * delimiters, they will not be applied automatically. The test() method throws
 * a \RuntimeException in case the regular expression is invalid.
 */
class Regex extends Predicate
{
    /**
     * the regular expression to use for validation
     *
     * @type  string
     */
    private $pattern;
    /**
     * map of pcre error codes and according error messages
     *
     * @array
     */
    private static $errors = [
            PREG_NO_ERROR              => 'invalid regular expression',
            PREG_INTERNAL_ERROR        => 'internal PCRE error',
            PREG_BACKTRACK_LIMIT_ERROR => 'backtrack limit exhausted',
            PREG_RECURSION_LIMIT_ERROR => 'recursion limit exhausted',
            PREG_BAD_UTF8_ERROR        => 'malformed UTF-8 data',
            PREG_BAD_UTF8_OFFSET_ERROR => 'did not end at valid UTF-8 codepoint',
            PREG_JIT_STACKLIMIT_ERROR  => 'failed because of limited JIT stack space'
    ];

    /**
     * constructor
     *
     * @param  string  $pattern  regular expression to use for validation
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * test that the given value complies with the regular expression
     *
     * @param   string  $value
     * @return  bool
     * @throws  \InvalidArgumentException  in case given value is not a string
     * @throws  \RuntimeException  in case the used regular expresion is invalid
     */
    public function test($value): bool
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(
                    'Given value of type "' . gettype($value)
                . '" can not be matched against a regular expression.'
            );
        }
        $check = @preg_match($this->pattern, $value);
        if (false === $check) {
            throw new \RuntimeException(sprintf(
                    'Failure while matching "%s", reason: %s.',
                    $this->pattern,
                    $this->messageFor(preg_last_error())
            ));
        }

        return 0 < $check;
    }

    /**
     * translates error code into proper error message
     *
     * @param   int  $errorCode
     * @return  string
     */
    private function messageFor(int $errorCode): string
    {
        if (isset(self::$errors[$errorCode])) {
            return self::$errors[$errorCode];
        }

        return 'Unknown error with error code ' . $errorCode;
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        return 'matches regular expression "' . $this->pattern . '"';
    }
}
