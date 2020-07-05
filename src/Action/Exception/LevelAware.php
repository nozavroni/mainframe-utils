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

namespace Mainframe\Action\Exception;

trait LevelAware
{
    /** @var array An array of arguments */
    protected array $args = [];

    /**
     * @param int $level The level value
     * @return $this
     */
    public function setLevel(int $level)
    {
        $this->args['level'] = $level;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getLevel()
    {
        return isset($this->args['level']) ? $this->args['level'] : null;
    }
}