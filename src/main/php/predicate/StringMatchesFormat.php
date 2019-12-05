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
 * Predicate to ensure a value complies to a given PHPUnit format expression.
 *
 * See the following documentation: https://phpunit.de/manual/current/en/appendixes.assertions.html#appendixes.assertions.assertStringMatchesFormat
 *
 * Adapted from https://github.com/sebastianbergmann/phpunit/blob/master/src/Framework/Constraint/StringMatchesFormatDescription.php
 */
class StringMatchesFormat extends Regex
{
    private $format;

    public function __construct(string $format)
    {
        $this->format = $format;
        $withoutCarriageReturn = \preg_replace('/\r\n/', "\n", $format);
        if (null === $withoutCarriageReturn) {
            throw new \RuntimeException('Could not replace carriage return in given format');
        }

        parent::__construct($this->patternForFormat($withoutCarriageReturn));
    }

    private function patternForFormat(string $format): string
    {
        $pattern = \preg_replace(
            [
                '/(?<!%)%e/', // %e: directory separator, for example / on Linux
                '/(?<!%)%s/', // %s: one or more of anything (character or white space) except the end of line character
                '/(?<!%)%S/', // %S: zero or more of anything (character or white space) except the end of line character
                '/(?<!%)%a/', // %a: one or more of anything (character or white space) including the end of line character
                '/(?<!%)%A/', // %A: zero or more of anything (character or white space) including the end of line character
                '/(?<!%)%w/', // %w: zero or more white space characters
                '/(?<!%)%i/', // %i: signed integer value, for example +3142, -3142
                '/(?<!%)%d/', // %d: unsigned integer value, for example 123456
                '/(?<!%)%x/', // %x: one or more hexadecimal character. That is, characters in the range 0-9, a-f, A-F
                '/(?<!%)%f/', // %f: floating point number, for example: 3.142, -3.142, 3.142E-10, 3.142e+10
                '/(?<!%)%c/'  // %c: single character of any sort
            ],
            [
                \str_replace('\\', '\\\\', '\\' . DIRECTORY_SEPARATOR),
                '[^\r\n]+',
                '[^\r\n]*',
                '.+',
                '.*',
                '\s*',
                '[+-]?\d+',
                '\d+',
                '[0-9a-fA-F]+',
                '[+-]?\.?\d+\.?\d*(?:[Ee][+-]?\d+)?',
                '.'
            ],
            \preg_quote($format, '/')
        );
        return '/^' . \str_replace('%%', '%', $pattern) . '$/s';
    }

    public function __toString(): string
    {
        return 'matches format "' . $this->format . '"';
    }
}
