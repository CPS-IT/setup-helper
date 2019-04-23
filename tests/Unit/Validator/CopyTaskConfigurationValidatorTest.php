<?php

namespace CPSIT\SetupHelper\Tests\Unit\Validator;

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\File\FileSystemInterface;
use CPSIT\SetupHelper\Task\TaskInterface;
use CPSIT\SetupHelper\Validator\CopyTaskConfigurationValidator;
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
 * Class CopyTaskConfigurationValidatorTest
 */
class CopyTaskConfigurationValidatorTest extends TestCase
{
    /**
     * @var CopyTaskConfigurationValidator
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

    public function setUp()
    {
        parent::setUp();
        $this->io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();
        $this->fileSystem = $this->getMockBuilder(FileSystemInterface::class)
            ->setMethods(['remove', 'exists'])
            ->getMockForAbstractClass();

        $this->subject = new CopyTaskConfigurationValidator($this->io, $this->fileSystem);
    }

    public function invalidConfigurationDataProvider()
    {

        return [
            'empty configuration' => [
                [],
                TaskInterface::MESSAGE_EMPTY_CONFIGURATION
            ],
            'source path empty' => [
                [
                    '' => 'foo'
                ],
                TaskInterface::MESSAGE_SOURCE_PATH_MUST_NOT_BE_EMPTY
            ],
            'source path 0' => [
                [
                    0 => 'foo'
                ],
                TaskInterface::MESSAGE_SOURCE_PATH_MUST_NOT_BE_EMPTY
            ],
            'target path empty' => [
                [
                    'foo' => ''
                ],
                TaskInterface::MESSAGE_TARGET_PATH_MUST_NOT_BE_EMPTY
            ]
        ];
    }
    /**
     * @dataProvider invalidConfigurationDataProvider
     * @param $configuration
     * @param $expectedMessage
     */
    public function testValidateWritesMessageForInvalidConfiguration($configuration, $expectedMessage)
    {
        $expectedMessage = \get_class($this->subject) . ': ' . $expectedMessage;
        $this->io->expects($this->once())
            ->method('write')
            ->with($expectedMessage);

        $result = $this->subject->validate($configuration);

        $this->assertFalse(
            $result
        );
    }

    public function testReturnsTrueForValidConfiguration()
    {
        $configuration = [
            'path/to/source' => 'path/to/target/'
        ];
        $this->io->expects($this->never())
            ->method('write');

        $result = $this->subject->validate($configuration);

        $this->assertTrue(
            $result
        );
    }
}