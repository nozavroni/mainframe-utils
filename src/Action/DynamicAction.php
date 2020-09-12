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
use Composer\Downloader\PathDownloader;
use Mainframe\Utils\Helper\Data;

class DynamicAction extends AbstractAction
{
    use Traits\ArgumentAware;

    /** @var Closure A callback to use as the action */
    protected Closure $action;

    /** @var object|null The object to bind the closure action to */
    protected ?object $that;

    /**
     * Bind the closure to the given object (as $this)
     *
     * @param object $that Object to bind the closure to
     */
    public function bind(object $that)
    {
        $this->that = $that;
        return $this;
    }

    /**
     * Set the callback that will be called when this action is invoked
     *
     * @param callable|mixed $action The callback for this action
     */
    public function setAction($action = null)
    {
        if (!is_callable($action)) {
            $action = fn(...$a) => $action;
        }
        $this->action = Closure::fromCallable($action);
        return $this;
    }

    public function __invoke(...$args)
    {
        return $this->perform(...$this->args);
    }

    /**
     * Perform this action (call its callback)
     *
     * @param mixed ...$args The args to pass to the closure
     * @return mixed
     */
    protected function perform(...$args)
    {
        return $this->action->call(
            $this->that ?? $this,
            ...$args
        );
    }
}