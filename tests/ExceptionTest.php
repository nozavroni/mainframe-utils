<?php
/**
 * Mainframe - Utilities
 *
 * A set of functions and classes used by all of the various Mainframe components and repositories.
 *
 * @author Luke Visinoni <luke.visinoni@gmail.com>
 * @copyright (c) 2020 Luke Visinoni <luke.visinoni@gmail.com>
 */
namespace MainframeTest\Utils;

use Mainframe\Utils\Exception\DomainException;
use Mainframe\Utils\Exception\Exception;
use Mainframe\Utils\Exception\InvalidArgumentException;
use Mainframe\Utils\Exception\OutOfBoundsException;
use Mainframe\Utils\Exception\OutOfRangeException;
use Throwable;

class ExceptionTest extends MainframeTestCase
{
    public function testSuppressOnlySuppressesDescendents()
    {
        $exception = InvalidArgumentException::create('{%name} is a %s {%word} who %s {%verb} {%proper-noun}', [
            'fucking',
            'word' => 'moron',
            'name' => 'Donald Trump',
            "doesn't",
            'verb' => 'deserve to be',
            'proper-noun' => 'President of the United States'
        ]);

        $suppressed = true;
        try {
            OutOfRangeException::suppress(function () use ($exception) {
                // this function causes an exception to be thrown and only OutOfRangeException should be suppressed
                throw $exception;
            });
        } catch (Throwable $t) {
            $suppressed = false;
        } finally {
            $this->assertFalse($suppressed);
        }

        $suppressed = true;
        try {
            InvalidArgumentException::suppress(function() use ($exception) {
                // this function causes an exception to be thrown and only InvalidArgumentException should be suppressed
                throw $exception;
            });
        } catch (Throwable $t) {
            $suppressed = false;
        } finally {
            $this->assertTrue($suppressed);
        }
    }

    public function testRecoverableExceptionOnlyRecoversFromItsOwn()
    {
        try {
            $recovered = true;
            $ret = 'none';
            $ret = OutOfBoundsException::recover(
                function () {
                    DomainException::raise();
                },
                function () {
                    return 'default';
                }
            );
        } catch (Throwable $throwable) {
            $recovered = false;
        } finally {
            $this->assertFalse($recovered);
            $this->assertEquals($ret, 'none');
        }

        try {
            $recovered = true;
            $ret = 'none';
            $ret = OutOfBoundsException::recover(
                function () {
                    OutOfBoundsException::raise();
                },
                function () {
                    return 'default';
                }
            );
        } catch (Throwable $throwable) {
            $recovered = false;
        } finally {
            $this->assertTrue($recovered);
            $this->assertEquals($ret, 'default');
        }
    }
}
