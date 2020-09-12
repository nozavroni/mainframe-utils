<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */

namespace Mainframe\Utils\Streams;

use GuzzleHttp\Psr7\LazyOpenStream;

use GuzzleHttp\Psr7\StreamDecoratorTrait;
use Psr\Http\Message\StreamInterface;
use function GuzzleHttp\Psr7\stream_for;
use function GuzzleHttp\Psr7\try_fopen;

/**
 * Lazily reads or writes to a file that is opened only after an IO operation
 * take place on the stream.
 */
class LazyStream implements StreamInterface
{
    use StreamDecoratorTrait;

    /** @var string File to open */
    protected string $filename;

    /** @var string $mode */
    protected string $mode;

    /** @var bool */
    protected bool $useIncludePath;

    /** @var array */
    protected ?array $context;

    /**
     * @param string $filename File to lazily open
     * @param string $mode fopen mode to use when opening the stream
     * @param bool $useIncludePath Whether to allow use of include path
     * @param array|null       context options (if any)
     */
    public function __construct($filename, $mode, bool $useIncludePath = true, ?array $context = null)
    {
        $this->filename = $filename;
        $this->mode = $mode;
        $this->useIncludePath = $useIncludePath;
        $this->context = $context;
    }

    /**
     * Creates the underlying stream lazily when required.
     *
     * @return StreamInterface
     */
    protected function createStream()
    {
        return stream_for(safe_fopen(
            $this->filename,
            $this->mode,
            $this->useIncludePath,
            $this->context
        ));
    }

    /**
     * Automatically close the resource
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Opens the stream and returns $this
     */
    public function __invoke(): self
    {
        $this->stream = $this->createStream();
        return $this;
    }

}
