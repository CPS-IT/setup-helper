<?php

namespace CPSIT\SetupHelper\Tests\Unit\File;

use Composer\Util\ProcessExecutor;
use CPSIT\SetupHelper\File\FileSystem;
use CPSIT\SetupHelper\File\FileSystemInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem as SFS;

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
 * Class FileSystemTest
 */
class FileSystemTest extends TestCase
{
    /**
     * @var FileSystem
     */
    protected $subject;

    /**
     * @var SFS|MockObject
     */
    protected $symfonyFS;

    /**
     * @var ProcessExecutor|MockObject
     */
    protected $processExecutor;

    public function setUp(): void
    {
        parent::setUp();
        $this->symfonyFS = $this->getMockBuilder(SFS::class)
            ->setMethods(
                [
                    'mkdir',
                    'exists',
                    'touch',
                    'chmod',
                    'chown',
                    'chgrp',
                    'symlink',
                    'hardlink',
                    'readlink'
                ]
            )->getMock();
        $this->processExecutor = $this->getMockBuilder(ProcessExecutor::class)
            ->getMock();
        $this->subject = new FileSystem($this->processExecutor, $this->symfonyFS);
    }

    public function testSubjectImplementsFileSystemInterface(): void
    {
        $this->assertInstanceOf(
            FileSystemInterface::class,
            $this->subject
        );
    }

    public function testConstructorSetsSymfonyFileSystem(): void
    {
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(
            \Symfony\Component\Filesystem\Filesystem::class,
            $this->subject->getSymfonyFileSystem()
        );
    }

    public function wrappedMethodsDataProvider(): array
    {
        return [
            // wrapper method, original method, arguments
            ['makeDirectories', 'mkdir', ['fooDirs', 2222], 'nil'],
            ['exists', 'exists', ['fileNames'], true],
            ['touch', 'touch', ['files', 123, 345], 'nil'],
            ['changeMode', 'chmod', ['files', 5, 1111, true], 'nil'],
            ['changeOwner', 'chown', ['files', 'userName', true], 'nil'],
            ['changeGroup', 'chgrp', ['files', 'groupName', true], 'nil'],
            ['symlink', 'symlink', ['originFile', 'targetFile', true], 'nil'],
            ['hardlink', 'hardlink', ['originFile', 'targetFile'], 'nil'],
            ['readLink', 'readlink', ['pathToRead', true], 'fooBar']
        ];
    }

    /**
     * @dataProvider wrappedMethodsDataProvider
     * @param string $method
     * @param string $expectedMethod
     * @param array $arguments
     * @param mixed $expectedResult
     */
    public function testSymfonyFileSystemMethodsAreWrapped(
        string $method,
        string $expectedMethod,
        array $arguments,
        $expectedResult
    ): void
    {
        $this->symfonyFS->expects($this->once())
            ->method($expectedMethod)
            ->willReturn($expectedResult);

        $result = call_user_func_array([$this->subject, $method], $arguments);

        if ('nil' !== $expectedResult) {
            $this->assertEquals(
                $expectedResult,
                $result
            );
        }
    }
}
