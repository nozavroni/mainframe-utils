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

namespace Mainframe\Utils\Options;

use Mainframe\Utils\Helper\Data;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait OptionsAware
{
    /** @var array The options array */
    protected array $options = [];

    /**
     * Configure options
     * This method must be defined by the base class. Without this method it really doesn't make sense
     * to use this trait, as this is how you define which options are allowed and how they should look.
     *
     * @param OptionsResolver $resolver The options resolver
     * @return void
     */
    abstract public function configureOptions(OptionsResolver $resolver);

    /**
     * Set options
     *
     * @param array $options The options array
     * @return $this
     */
    public function setOptions(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
        return $this;
    }

    /**
     * Get an option value
     *
     * @param string $name The option name
     * @return mixed
     */
    public function getOption($name)
    {
        return Data::get($this->options, $name);
    }

    /**
     * Get options array
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}