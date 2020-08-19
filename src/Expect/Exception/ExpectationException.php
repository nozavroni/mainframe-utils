<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert\Exception;

use Mainframe\Utils\Assert\Rules\RuleInterface;
use Mainframe\Utils\Exception\AssertionException;

class ValidationException extends AssertionException
{
    protected RuleInterface $rule;

    /**
     * @return RuleInterface
     */
    public function getRule(): RuleInterface
    {
        return $this->rule;
    }

    /**
     * @param RuleInterface $rule
     */
    public function setRule(RuleInterface $rule): self
    {
        $this->rule = $rule;
        return $this;
    }
}