<?php
/**
 * Mainframe - a domain codification framework
 *
 * A clumsy attempt to put a name to a concept I've been kicking around for quite some time now. See the
 * README file for a more in-depth overview of this concept and how this library relates to it.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Support\Action;

use Closure;

class DynamicAction extends AbstractAction
{
    /** @var Closure A callback to use as the action */
    protected $action;

    public function setAction(?callable $action = null)
    {
        if (is_null($action)) {
            $action = fn () => null;
        }
        $this->action = Closure::fromCallable($action);
    }

    protected function perform()
    {
        return $this->action->call(
            $this,
            ...func_get_args()
        );
    }
}