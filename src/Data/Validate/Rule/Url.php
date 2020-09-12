<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace Mainframe\Utils\Data\Validate\Rule;

use Mainframe\Utils\Data\Value;use Mainframe\Utils\Options\OptionsAware;
use Mainframe\Utils\Options\OptionsAwareInterface;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            use Psr\Http\Message\StreamInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Url extends Rule implements OptionsAwareInterface
{
    use OptionsAware;

    /**
     * FileExists constructor.
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * Configure options
     * This method must be defined by the base class. Without this method it really doesn't make sense
     * to use this trait, as this is how you define which options are allowed and how they should look.
     *
     * @param OptionsResolver $resolver The options resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'require_path' => false,
            'require_query' => false,
        ]);
    }

    public function validate(Value $value): bool
    {
        $flags = 0;
        if ($this->getOption('require_path')) {
            flag_set($flags, FILTER_FLAG_PATH_REQUIRED);
        }
        if ($this->getOption('require_query')) {
            flag_set($flags, FILTER_FLAG_QUERY_REQUIRED);
        }
        return (bool) filter_var($value(), FILTER_VALIDATE_URL, $flags);
    }
}