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

use Mainframe\Utils\Data\Validate\Exception\AssertionFailedException;
use Mainframe\Utils\Data\Value;
use Mainframe\Utils\Exception\RuntimeException;
use Mainframe\Utils\Options\OptionsAware;
use Mainframe\Utils\Options\OptionsAwareInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class File extends Rule implements OptionsAwareInterface
{
    use OptionsAware;

    const TYPE_FILE = 'file';
    const TYPE_DIR = 'dir';
    const TYPE_LINK = 'link';

    const IS_READABLE = 1 << 0;
    const IS_WRITABLE = 1 << 1;
    const IS_SEEKABLE = 1 << 2;
    const IS_EXECUTABLE = 1 << 3;
    const IS_UPLOADED = 1 << 4;

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
        $resolver
            ->setDefaults([
                'type' => static::TYPE_FILE,
            ])
            ->setAllowedValues('type', [
                static::TYPE_FILE,
                static::TYPE_DIR,
                static::TYPE_LINK,
            ])
            ->setAllowedTypes('attribs', 'int');
    }

    public function validate(Value $value): bool
    {
        $useIncl = $this->getOption('use_include_path');
        $type = $this->getOption('type');
        $flag = $this->getOption('attribs');
        $isType = sprintf('is_%s', $type);

        /** @var StreamInterface $stream */
        AssertionFailedException::raiseUnless(
            $stream = RuntimeException::recover(
                function () use ($value, $useIncl) {
                    return lazy_fopen($value(), 'r+', $useIncl);
                },
                false
            ),
            'File or directory does not exist: %s',
            [$value()]
        );
        AssertionFailedException::raiseUnless(
            $isType($value()),
            "%s is not a valid %s",
            [$value(), $type]
        );
        AssertionFailedException::raiseIf(
            flag_assert($flag, static::IS_READABLE) && !$stream->isReadable(),
            '%s is not readable',
            [$value()]
        );
        AssertionFailedException::raiseIf(
            flag_assert($flag, static::IS_WRITABLE) && !$stream->isWritable(),
            '%s is not writable',
            [$value()]
        );
        AssertionFailedException::raiseIf(
            flag_assert($flag, static::IS_SEEKABLE) && !$stream->isSeekable(),
            '%s is not seekable',
            [$value()]
        );
        AssertionFailedException::raiseIf(
            flag_assert($flag, static::IS_UPLOADED) && !is_uploaded_file($value()),
            '%s is not uploaded',
            [$value()]
        );
        return true;
    }
}