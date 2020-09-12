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

use Symfony\Component\OptionsResolver\OptionsResolver;

interface OptionsAwareInterface
{
    public function setOptions(array $options = []);

    public function getOptions(): array;

    public function getOption($name);
}
