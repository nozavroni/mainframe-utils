<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Assert;

use Mainframe\Utils\Assert\Exception\ValidationException;
use Mainframe\Utils\Assert\Rules\RuleInterface;
use Mainframe\Utils\Data\Collection;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

/**
 * @method bool or(...$callbacks)
 *
 */
class Assertion
{
    protected Collection $assertions;

    protected Collection $errors;

    public function __construct()
    {
        $this->assertions = new Collection();
        $this->errors = new Collection();
    }

    public function __call($name, array $args)
    {
        $class = '\\Mainframe\\Utils\\Assert\\Rules\\' . $name . 'Rule';
        /** @var RuleInterface $assert */
        $assert = new $class(...$args);
        $this->assertions->push($assert);
    }

    public function __invoke($value): bool
    {
        return ValidationException::recover (
            function () use ($value) {
                $this->validate($value);
                return true;
            },
            false,
            fn ($error) => $this->registerError($error)
        );
    }

    public function registerError(ValidationException $error)
    {
        $this->errors[] = $error;
        return $this;
    }

    protected function validate($value): void
    {
        foreach ($this->assertions->toArray() as $assert) {
            if (!value_of($assert, $value)) {
                throw ValidationException::create("Validation failed")
                    ->setRule($assert);
            }
        }
    }
}