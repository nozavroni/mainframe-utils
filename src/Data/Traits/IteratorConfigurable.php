<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data\Traits;

/**
 * Trait IteratorConfigurable
 * This trait is intended to be used with the OptionsAware trait / interface and either a data container
 * or a data container decorator.
 */
trait IteratorConfigurable
{
    public function getIterator()
    {
        $iterClass = $this->getOption('iterator_class');
        if (!class_exists($iterClass)) {

        }
     }

    abstract public function getOption();
}