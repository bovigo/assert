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
     * @param  mixed                                 $value
     * @param  \SebastianBergmann\Exporter\Exporter  $exporter
     */
    public function __construct($value, Exporter $exporter)
    {
        $this->value    = $value;
        $this->exporter = $exporter;
    }

    /**
     * evaluates predicate against value, throwing an AssertionFailure when predicate fails
     *
     * @param   \bovigo\assert\predicate\Predicate  $predicate    predicate to test given value
     * @param   string                              $description  optional  additional description for failure message
     * @return  bool
     * @throws  \bovigo\assert\AssertionFailure
     */
    public function evaluate(Predicate $predicate, string $description = null): bool
    {
        try {
            if ($predicate->test($this->value)) {
                return true;
            }
        } catch (\Exception $e) {
            if (empty($description)) {
                $description = $e->getMessage();
            } else {
                $description .= "\n" . $e->getMessage();
            }
        }

        throw new AssertionFailure(
                $this->describeFailure($predicate, $description)
        );
    }

    /**
     * creates failure description when value failed the test with given predicate
     *
     * @param   \bovigo\assert\predicate\Predicate  $predicate    predicate that failed
     * @param   string                              $description  additional description for failure message
     * @return  string
     */
    private function describeFailure(Predicate $predicate, string $description = null): string
    {
        $predicateText = (string) $predicate;
        $failureDescription = sprintf(
                'Failed asserting that %s %s%s',
                $predicate->describeValue($this->exporter, $this->value),
                $predicateText,
                strpos($predicateText, "\n") !== false ? '' : '.'
        );

        if (!empty($description)) {
            $failureDescription .= "\n" . $description;
        }

        return $failureDescription;
    }
}
