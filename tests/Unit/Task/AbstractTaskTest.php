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
use CPSIT\SetupHelper\Task\AbstractTask;
use Naucon\File\File;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractTaskTest extends TestCase
{
    /**
     * @var AbstractTask|MockObject
     */
    protected $subject;

    /**
     * @var IOInterface|MockObject
     */
    protected $io;

    /**
     * @var array
     */
    protected $config = ['foo'];

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $this->io = $this->getMockBuilder(IOInterface::class)
            ->getMockForAbstractClass();
        $this->subject = $this->getMockBuilder(AbstractTask::class)
            ->setConstructorArgs([$this->io, $this->config])
            ->getMockForAbstractClass();
    }

    public function testConstructorSetsIo()
    {
        $this->assertSame(
            $this->io,
            $this->subject->getIo()
        );
    }

    public function testConstructorSetsConfig()
    {
        $this->assertSame(
            $this->config,
            $this->subject->getConfig()
        );
    }

    public function testDefaultConfigIsEmptyArray()
    {
        // constructor argument config empty
        $constructorArguments = [$this->io];
        $expectedConfig = [];

        $this->subject = $this->getMockBuilder(AbstractTask::class)
            ->setConstructorArgs($constructorArguments)
            ->getMockForAbstractClass();
        $this->assertSame(
            $expectedConfig,
            $this->subject->getConfig()
        );
    }

    public function testGetWorkingDirectoryReturnsWorkingDirectory()
    {
        $expectedDirectory = getcwd() . File::PATH_SEPARATOR;
        $this->assertSame(
            $expectedDirectory,
            $this->subject->getWorkingDirectory()
        );
    }

    public function testConfigCanBeSet()
    {
        $config = ['boo'];

        $this->subject->setConfig($config);
        $this->assertSame(
            $config,
            $this->subject->getConfig()
        );
    }
}
