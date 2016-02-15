<?php
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
     * @param  \Exception  $actualException
     */
    public function __construct(\Exception $actualException)
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
    public function withMessage($expectedMessage)
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
    public function message($predicate)
    {
        assert(
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
    public function withCode($expectedCode)
    {
        assert(
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
     * @return  \bovigo\assert\CatchedException
     */
    public function with($predicate)
    {
        assert($this->actualException, $predicate);
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
    public function after($value, $predicate, $description = null)
    {
        assert($value, $predicate, $description);
        return $this;
    }
}
