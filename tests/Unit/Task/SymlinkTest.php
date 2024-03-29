<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Michael Scheppat
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
use CPSIT\SetupHelper\File\FileSystemInterface;
use CPSIT\SetupHelper\Task\Symlink;
use CPSIT\SetupHelper\Task\TaskInterface;
use Naucon\File\File;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class SymlinkTest
 */
class SymlinkTest extends TestCase
{
    /**
     * @var Symlink
     */
    protected $subject;

    /**
     * @var IOInterface|MockObject
     */
    protected $io;

    /**
     * @var vfsStreamDirectory
     */
    protected $virtualDirectory;

    /**
     * @var FileSystemInterface|MockObject
     */
    protected $fileSystem;

    public function setUp(): void
    {
        parent::setUp();
        vfsStreamWrapper::register();
        $this->virtualDirectory = vfsStream::setup('root');

        $this->io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();
        $this->fileSystem = $this->getMockBuilder(FileSystemInterface::class)
            ->setMethods(['symlink', 'exists'])
            ->getMockForAbstractClass();

        $this->subject = new Symlink($this->io, [], $this->fileSystem);
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

        $sourceFilePath = 'sourceFolder' . File::PATH_SEPARATOR . 'sourceFile.txt';
        $linkPath = 'targetFolder' . File::PATH_SEPARATOR . 'symlinkToSourceFile';

        $this->assertFileNotExists($linkPath);

        $config = [
            $sourceFilePath => $linkPath
        ];
        $this->subject = $this->getMockBuilder(Symlink::class)
            ->setConstructorArgs([$this->io, $config, $this->fileSystem])
            ->setMethods(['getWorkingDirectory'])
            ->getMock();

        $this->fileSystem->expects($this->exactly(2))
            ->method('exists')
            ->withConsecutive(
                [$sourceFilePath],
                [$linkPath]
            )
            ->willReturnOnConsecutiveCalls(true, false);

        $this->fileSystem->expects($this->once())
            ->method('symlink')
            ->willReturn(true);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_SYMLINK_CREATED,
            $sourceFilePath,
            $linkPath
        );
        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);
        $this->io->expects($this->never())
            ->method('writeError');

        $this->subject->perform();
    }

    public function testPerformWritesErrorForMissingSourceFile()
    {
        $sourceFilePath = 'sourceFolder' . File::PATH_SEPARATOR . 'sourceFile.txt';
        $linkPath = 'targetFolder' . File::PATH_SEPARATOR . 'symlinkToSourceFile';

        $config = [
            $sourceFilePath => $linkPath
        ];


        $this->subject = new Symlink($this->io, $config, $this->fileSystem);
        $expectedAbsoluteSourceFilePath = $this->subject->getWorkingDirectory() . $sourceFilePath;

        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->with($sourceFilePath)
            ->willReturn(false);

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_FILE_NOT_FOUND,
            $expectedAbsoluteSourceFilePath
        );
        $this->io->expects($this->once())
            ->method('writeError')
            ->with($expectedMessage);
        $this->fileSystem->expects($this->never())
            ->method('symlink');
        $this->io->expects($this->never())
            ->method('write');

        $this->subject->perform();
    }

    public function testPerformWritesErrorForExistingFile()
    {
        $sourceFilePath = 'sourceFolder' . File::PATH_SEPARATOR . 'sourceFile.txt';
        $linkPath = 'targetFolder' . File::PATH_SEPARATOR . 'symlinkToSourceFile';

        $config = [
            $sourceFilePath => $linkPath
        ];

        $this->subject = new Symlink($this->io, $config, $this->fileSystem);

        $this->fileSystem->expects($this->exactly(2))
            ->method('exists')
            ->withConsecutive(
                [$sourceFilePath],
                [$linkPath]
            )
            ->willReturnOnConsecutiveCalls(
                true, true
            );

        $explanation = ' as a file.';

        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_SYMLINK_ALREADY_EXISTS,
            $linkPath, $explanation
        );

        $this->io->expects($this->once())
            ->method('writeError')
            ->with($expectedMessage);
        $this->fileSystem->expects($this->never())
            ->method('symlink');
        $this->io->expects($this->never())
            ->method('write');

        $this->subject->perform();
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
        $this->subject = $this->getMockBuilder(Symlink::class)
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
