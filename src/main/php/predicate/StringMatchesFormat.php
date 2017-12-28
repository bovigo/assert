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
    private $initialPattern;

    public function __construct(string $pattern)
    {
        $this->initialPattern = $pattern;

        $pattern = $this->createPatternFromFormat(\preg_replace('/\r\n/', "\n", $pattern));

        parent::__construct($pattern);
    }

    private function createPatternFromFormat(string $string): string
    {
        $string = \preg_replace(
            [
                '/(?<!%)%e/',
                '/(?<!%)%s/',
                '/(?<!%)%S/',
                '/(?<!%)%a/',
                '/(?<!%)%A/',
                '/(?<!%)%w/',
                '/(?<!%)%i/',
                '/(?<!%)%d/',
                '/(?<!%)%x/',
                '/(?<!%)%f/',
                '/(?<!%)%c/'
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
            \preg_quote($string, '/')
        );

        $string = \str_replace('%%', '%', $string);

        return '/^' . $string . '$/s';
    }

    public function __toString(): string
    {
        return 'matches regular expression "' . $this->initialPattern . '"';
    }
}
