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
 * Class for validating that a string denotes an existing path.
 */
abstract class FilesystemPredicate extends Predicate
{
    /**
     * If no base path is given the test will be done against the whole
     * file system, given values can not be relative then.
     */
    public function __construct(private ?string $basePath = null) { }

    /**
     * test that the given value is represents an existing path
     */
    public function test(mixed $value): bool
    {
        if (empty($value)) {
            return false;
        }

        if (null !== $this->basePath) {
            return $this->fileExists($this->basePath . DIRECTORY_SEPARATOR . $value);
        }

        return $this->fileExists($value) || $this->fileExists(getcwd() . DIRECTORY_SEPARATOR . $value);
    }

    /**
     * checks if given file exists
     */
    abstract protected function fileExists(string $path): bool;

    /**
     * returns string representation of predicate
     */
    public function __toString(): string
    {
        return sprintf(
            'is a existing %s%s',
            $this->type(),
            null !== $this->basePath ? ' in basepath ' . $this->basePath : ''
        );
    }

    /**
     * returns file system type which is checked
     */
    abstract protected function type(): string;
}
