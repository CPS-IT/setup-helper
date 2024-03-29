<?php

namespace CPSIT\SetupHelper\Tests\Unit\Task;

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\File\FileSystemInterface;
use CPSIT\SetupHelper\Task\Copy;
use CPSIT\SetupHelper\Task\TaskInterface;
use CPSIT\SetupHelper\Validator\ConfigurationValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

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

/**
 * Class CopyTest
 */
class CopyTest extends TestCase
{
    /**
     * @var Copy|MockObject
     */
    protected $subject;

    /**
     * @var FileSystemInterface|MockObject
     */
    protected $fileSystem;

    /**
     * @var IOInterface|MockObject
     */
    protected $io;

    /**
     * @var ConfigurationValidatorInterface|MockObject
     */
    protected $configurationValidator;

    public function setUp(): void
    {
        parent::setUp();
        $this->io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();
        $this->fileSystem = $this->getMockBuilder(FileSystemInterface::class)
            ->setMethods(['copy', 'exists'])
            ->getMockForAbstractClass();
        $this->configurationValidator = $this->getMockBuilder(ConfigurationValidatorInterface::class)
            ->setMethods(['validate'])
            ->getMockForAbstractClass();

        $this->subject = new Copy($this->io, [], $this->fileSystem, $this->configurationValidator);
    }

    public function testCopyImplementsTaskInterface(): void
    {
        $this->assertInstanceOf(
            TaskInterface::class,
            $this->subject
        );
    }

    public function testGetConfigurationValidatorReturnsInstanceOfConfigurationValidatorInterface(): void
    {
        $this->assertInstanceOf(
            ConfigurationValidatorInterface::class,
            $this->subject->getConfigurationValidator()
        );
    }

    public function testConstructorSetsConfigurationValidator(): void
    {
        $this->subject->__construct($this->io, [], $this->fileSystem, $this->configurationValidator);

        $this->assertSame(
            $this->configurationValidator,
            $this->subject->getConfigurationValidator()
        );
    }

    public function testPerformValidatesConfiguration(): void
    {
        $configuration = ['foo'];

        $this->subject->setConfig($configuration);
        $this->configurationValidator->expects($this->once())
            ->method('validate')
            ->with($configuration)
            ->willReturn(false);

        $this->subject->perform();
    }

    public function testPerformWritesErrorForException(): void
    {
        $message = 'bar';
        $mockException = new \Exception($message);
        $this->subject = $this->getMockBuilder(Copy::class)
            ->setMethods(['process'])
            ->setConstructorArgs(
                [$this->io, [], $this->fileSystem, $this->configurationValidator])
            ->getMock();

        $this->configurationValidator->method('validate')
            ->willReturn(true);
        $this->subject->method('process')
            ->willThrowException($mockException);

        $this->io->expects($this->once())
            ->method('writeError')
            ->with($message);

        $this->subject->perform();
    }

    public function testPerformCopies(): void
    {
        $source = 'path/to/file';
        $target = 'path/to/target';
        $configuration = [
            $source => $target
        ];
        $this->subject->setConfig($configuration);

        $this->configurationValidator->method('validate')
            ->willReturn(true);
        $this->fileSystem->expects($this->once())
            ->method('copy')
            ->with($source, $target);
        $this->subject->perform();
    }

    public function testPerformWritesMessageOnSuccess()
    {
        $sourceFolderName = 'foo';
        $targetFolderName = 'bar';
        $config = [
            $sourceFolderName => $targetFolderName
        ];
        $this->subject->setConfig($config);
        $expectedMessage = sprintf(
            TaskInterface::MESSAGE_FILE_COPIED,
            $sourceFolderName,
            $targetFolderName
        );

        $this->configurationValidator->method('validate')
            ->willReturn(true);
        $this->fileSystem->method('copy')
            ->willReturn(true);

        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);
        $this->subject->perform();
    }
}
