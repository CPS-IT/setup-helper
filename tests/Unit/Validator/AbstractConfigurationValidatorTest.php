<?php

namespace CPSIT\SetupHelper\Tests\Unit\Validator;

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\File\FileSystemInterface;
use CPSIT\SetupHelper\Validator\AbstractConfigurationValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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
 * Class AbstractConfigurationValidatorTest
 */
class AbstractConfigurationValidatorTest extends TestCase
{
    /**
     * @var AbstractConfigurationValidator|MockObject
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

    public function setUp(): void
    {
        parent::setUp();
        $this->io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();
        $this->fileSystem = $this->getMockBuilder(FileSystemInterface::class)
            ->getMockForAbstractClass();

        $this->subject = $this->getMockBuilder(AbstractConfigurationValidator::class)
            ->setConstructorArgs([$this->io, $this->fileSystem])
            ->getMockForAbstractClass();
    }

    public function testConstructorSetsIO()
    {
        $this->subject->__construct($this->io);

        $this->assertInstanceOf(
            IOInterface::class,
            $this->subject->getIo()
        );
        $this->assertSame(
            $this->io,
            $this->subject->getIo()
        );
    }

    public function testConstructorSetsFileSystemWithoutConstructorArgument()
    {
        $this->subject->__construct($this->io);
        $this->assertInstanceOf(
            FileSystemInterface::class,
            $this->subject->getFileSystem()
        );
    }

    public function testConstructorSetsFileSystemFromConstructorArgument()
    {
        $this->subject->__construct($this->io, $this->fileSystem);
        $this->assertSame(
            $this->fileSystem,
            $this->subject->getFileSystem()
        );
    }
}
