<?php
declare(strict_types=1);
/**
 * This file is part of bovigo\assert.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert\predicate;
use SebastianBergmann\Exporter\Exporter;
/**
 * Allows to wrap another predicate to decorate its messages.
 *
 * @internal
 * @since  1.6.0
 */
class Wrap extends Predicate
{
    /**
     * @var  \bovigo\assert\predicate\Predicate
     */
    private $predicate;
    /**
     * @var  string
     */
    private $wrappedDesciption;

    /**
     * constructor
     *
     * @param  \bovigo\assert\predicate\Predicate  $predicate
     * @param  string                              $wrappedDesciption
     */
    public function __construct(Predicate $predicate, string $wrappedDesciption)
    {
        $this->predicate         = $predicate;
        $this->wrappedDesciption = $wrappedDesciption;
    }

    /**
     * evaluates predicate against given value
     *
     * @param   mixed  $value
     * @return  bool
     */
    public function test($value): bool
    {
        return $this->predicate->test($value);
    }

    /**
     * returns string representation of predicate
     *
     * @return  string
     */
    public function __toString(): string
    {
        return (string) $this->predicate;
    }

    /**
     * returns a textual description of given value
     *
     * @param   \SebastianBergmann\Exporter\Exporter  $exporter
     * @param   mixed                                 $value
     * @return  string
     */
    public function describeValue(Exporter $exporter, $value): string
    {
        return sprintf(
                $this->wrappedDesciption,
                $this->predicate->describeValue($exporter, $value)
        );
    }
}
