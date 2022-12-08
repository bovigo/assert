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
    public function __construct(private $value, private Exporter $exporter) { }

    /**
     * evaluates predicate against value, throwing an AssertionFailure when predicate fails
     *
     * @throws  AssertionFailure
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
