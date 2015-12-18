<?php
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
/**
 * Predicate to test that something is a specific internal PHP type.
 */
class IsOfType extends Predicate
{
    /**
     * the expected type
     *
     * @type  string
     */
    private $expectedType;
    /**
     * map of internal types and their according test functions
     *
     * @type  array
     */
    private static $types = [
        'array'    => 'is_array',
        'boolean'  => 'is_bool',
        'bool'     => 'is_bool',
        'double'   => 'is_float',
        'float'    => 'is_float',
        'integer'  => 'is_integer',
        'int'      => 'is_integer',
        'numeric'  => 'is_numeric',
        'object'   => 'is_object',
        'resource' => 'is_resource',
        'string'   => 'is_string',
        'scalar'   => 'is_scalar',
        'callable' => 'is_callable'
    ];

    /**
     * constructor
     *
     * @param   int   $expectedType  name of type to test for
     * @throws  \InvalidArgumentException  in case expected type is unknown
     */
    public function __construct($expectedType)
    {
        if (!isset(self::$types[$expectedType])) {
            throw new \InvalidArgumentException(
                    'Unknown internal type ' . $expectedType
            );
        }

        $this->expectedType = $expectedType;
    }

    /**
     * test that the given value is of a certain size
     *
     * @param   string|array|\Countable|\Traversable  $value
     * @return  bool   true if size of value is equal to expected size, else false
     * @throws  \InvalidArgumentException
     */
    public function test($value)
    {
        $function = self::$types[$this->expectedType];
        return $function($value);
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString()
    {
        return sprintf('is of type "%s"', $this->expectedType);
    }
}
