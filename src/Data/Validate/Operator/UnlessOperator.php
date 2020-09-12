<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Data\Validate\Operator;

use Composer\Downloader\PathDownloader;
use Mainframe\Utils\Data\Validate\RuleSetInterface;
use Mainframe\Utils\Data\Value;

class sUnlessOperator extends Operator
{
    public function __construct($condition, $then, $else = null)
    {
        $this->condition = $condition;
        $this->then = $then;
        $this->else = $else;
    }

    protected function operate(Value $value): bool
    {
        if (!$this->condition($value)) {
            return $this->then($value);
        } else {
            if (!is_null($this->else)) {
                return $this->else($value);
            }
        }
        return false;
    }
}