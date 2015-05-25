<?php
/**
 * This file is part of bovigo\callmap.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bovigo\assert;
use bovigo\assert\predicate\Equals;
use bovigo\assert\predicate\IsFalse;
use bovigo\assert\predicate\IsIdentical;
use bovigo\assert\predicate\IsInstanceOf;
use bovigo\assert\predicate\IsTrue;
use bovigo\assert\predicate\Predicate;
use SebastianBergmann\Exporter\Exporter;
/**
 * Allows to evaluate predicates on a given value.
 */
class Assertion
{
    /**
     * value to do the assertion on
     *
     * @type  mixed
     */
    private $value;
    /**
     * @type  \SebastianBergmann\Exporter\Exporter
     */
    private $exporter;

    /**
     * constructor
     *
     * @param  string                                $value
     * @param  \SebastianBergmann\Exporter\Exporter  $exporter
     */
    public function __construct($value, Exporter $exporter)
    {
        $this->value    = $value;
        $this->exporter = $exporter;
    }

    /**
     * asserts that value is equal to expected value
     *
     * @param   mixed   $expected  expected value
     * @param   string  $message   optional  additional description for failure message
     * @param   float   $delta     optional  allowed numerical distance between two values to consider them equal
     * @return  bool
     */
    public function equals($expected, $message = null, $delta = 0.0)
    {
        return $this->evaluate(new Equals($expected, $delta), $message);
    }

    /**
     * asserts that value is false
     *
     * @param   string  $message   optional  additional description for failure message
     * @return  bool
     */
    public function isFalse($message = null)
    {
        return $this->evaluate(IsFalse::instance(), $message);
    }

    /**
     * asserts that value is true
     *
     * @param   string  $message   optional  additional description for failure message
     * @return  bool
     */
    public function isTrue($message = null)
    {
        return $this->evaluate(IsTrue::instance(), $message);
    }

    /**
     * asserts that value is an instance of the expected type
     *
     * @param   string  $expectedType  name of expected type
     * @param   string  $message       optional  additional description for failure message
     * @return  bool
     */
    public function isInstanceOf($expectedType, $message = null)
    {
        return $this->evaluate(
                new IsInstanceOf($expectedType),
                $message,
                'shortenedExport'
        );
    }

    /**
     * asserts that both expected and actual reference the same value
     *
     * @param   mixed   $expected  expected value
     * @param   string  $message   optional  additional description for failure message
     * @return  bool
     */
    function isSameAs($expected, $message = null)
    {
        if (is_bool($expected) && is_bool($this->value)) {
            return $this->equals($expected, $message);
        }

        return $this->evaluate(new IsIdentical($expected), $message);
    }

    /**
     * evaluates predicate against value, throwing an AssertionFailure when predicate fails
     *
     * @param   \bovigo\assert\predicate\Predicate|callable  $test         predicate or callable to test given value
     * @param   string                                       $description  optional  additional description for failure message
     * @param   string                                       $export       optional  how value should be exported to string in case of failure
     * @return  bool
     * @throws  \bovigo\assert\AssertionFailure
     */
    function evaluate($test, $description = null, $export = 'export')
    {
        $predicate = Predicate::castFrom($test);
        if ($predicate->test($this->value)) {
            return true;
        }

        throw new AssertionFailure(
                $this->describeFailure($predicate, $export, $description)
        );
    }

    /**
     * creates failure description when value failed the test with given predicate
     *
     * @param   \bovigo\assert\predicate\Predicate  $predicate    predicate that failed
     * @param   string                              $export       how value should be exported to string
     * @param   string                              $description  additional description for failure message
     * @return  string
     */
    private function describeFailure(Predicate $predicate, $export, $description)
    {
        // Leaky abstraction from Equals predicate: leaks texts from
        // SebastianBergmann\Comparator
        $predicateText = (string) $predicate;
        if (substr($predicateText, 0, 6) !== 'Failed') {
            $failureDescription = sprintf(
                    'Failed asserting that %s %s',
                    $this->exporter->$export($this->value),
                    $predicateText
            );
        } else {
            $failureDescription = $predicateText;
        }

        if (!empty($description)) {
            $failureDescription = $description . "\n" . $failureDescription;
        }

        return $failureDescription;
    }
}
