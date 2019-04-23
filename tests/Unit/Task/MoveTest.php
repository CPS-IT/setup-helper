<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace CPSIT\SetupHelper\Tests\Unit\Task;

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\Task\Move;
use CPSIT\SetupHelper\Task\TaskInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class MoveTest
 */
class MoveTest extends TestCase
{
    /**
     * @var Move
     */
    protected $subject;

    /**
     * @var IOInterface|MockObject
     */
    protected $io;


    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $this->io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();

        $this->subject = new Move($this->io);
    }

    public function testPerformWritesMessageForEmptyConfiguration()
    {
        $expectedMessage = \get_class($this->subject) . ': ' .
            TaskInterface::MESSAGE_EMPTY_CONFIGURATION;
        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);

        $this->subject->perform();
    }


    public function testPerformWritesMessageForSuccess()
    {
        $source = 'foo.txt';
        $target = 'bar';
        mkdir($target);
        $fileHandle = fopen($source, 'ab');
        fwrite($fileHandle, 'foo');
        fclose($fileHandle);

        $config = [
            $source => $target
        ];
        $this->subject = new Move($this->io, $config);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_FILE_MOVED,
            $source, $target
        );
        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);
        $this->io->expects($this->never())
            ->method('writeError');

        $this->subject->perform();

        unlink($target . '/' . $source);
        rmdir($target);
    }

    public function testPerformWritesErrorForException(): void
    {
        $message = 'bar';
        $mockException = new \Exception($message);
        $source = 'foo.txt';
        $target = 'bar';

        $config = [
            $source => $target
        ];
        $this->subject = $this->getMockBuilder(Move::class)
            ->setMethods(['process'])
            ->setConstructorArgs(
                [$this->io, $config])
            ->getMock();

        $this->subject->method('process')
            ->willThrowException($mockException);

        $this->io->expects($this->once())
            ->method('writeError')
            ->with($message);

        $this->subject->perform();
    }
}
