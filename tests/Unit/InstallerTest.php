<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 Dirk Wenzel <wenzel@cps-it.de>
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

namespace CPSIT\SetupHelper\Tests\Unit;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\RootPackageInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use CPSIT\SetupHelper\Installer;
use CPSIT\SetupHelper\SettingsInterface as SI;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class InstallerTest extends TestCase
{
    /**
     * @var Installer
     */
    protected $subject;

    /**
     * @var string
     */
    protected $fileName = '5dede1ee63ddef35a178de1b38b91ef69b5b437d96bcc3220e59c30d39d8ba86';

    /**
     * @var bool|resource
     */
    protected $fileHandle;


    public function setUp(): void
    {
        $this->subject = new Installer();
        $this->fileHandle = fopen($this->fileName, 'ab');
        fwrite($this->fileHandle, 'foo');
        fclose($this->fileHandle);
    }

    public function tearDown(): void
    {
        if (is_file($this->fileName)) {
            unlink($this->fileName);
        }

    }

    public function testGetSubscribedEvents()
    {
        $subscribedEvents = Installer::getSubscribedEvents();
        $this->assertArrayHasKey(
            ScriptEvents::POST_INSTALL_CMD,
            $subscribedEvents
        );

        $this->assertEquals(
            $subscribedEvents[ScriptEvents::POST_INSTALL_CMD],
            Installer::ENTRY_METHOD_NAME
        );
    }

    /**
     * @return array
     */
    public function performTasksDataProvider(): array
    {
        $nonExistingFilePath = '7cd1e9d0f2ac1b9c7c65f3fb7c85962c6ed774196c4470af4f92a17ab0983338';

        return [
            // extra,
            'empty extra' => [
                []
            ],
            'unlink: file not found' => [
                [
                    SI::INSTALLER_EXTRA_KEY => [
                        [SI::UNLINK_TASK_KEY => [$nonExistingFilePath]]
                    ]
                ]
            ],
            'unlink: file deleted' => [
                [
                    SI::INSTALLER_EXTRA_KEY => [
                        [SI::UNLINK_TASK_KEY => [$this->fileName]]
                    ]
                ]
            ]
        ];

    }


    public function testPerformTaskWritesErrorForInvalidTaskKeyInConfig()
    {
        $invalidTaskKey = 'invalidFooTask';

        $extra = [
            SI::INSTALLER_EXTRA_KEY => [
                [$invalidTaskKey => []]
            ]
        ];
        $expectedMessage = sprintf(Installer::MESSAGE_INVALID_TASK_KEY, $invalidTaskKey);

        $package = $this->getMockBuilder(RootPackageInterface::class)
            ->setMethods(['getExtra'])
            ->getMockForAbstractClass();
        $io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();

        $composer = $this->getMockBuilder(Composer::class)
            ->setMethods(['getPackage'])
            ->getMock();
        /** @var Event|MockObject $composerEvent */
        $composerEvent = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMethods(['getComposer', 'getIo'])
            ->getMock();
        $composerEvent->expects($this->once())
            ->method('getIo')
            ->willReturn($io);

        $composerEvent->expects($this->once())
            ->method('getComposer')
            ->willReturn($composer);
        $composer->expects($this->once())
            ->method('getPackage')
            ->willReturn($package);
        $package->expects($this->once())
            ->method('getExtra')
            ->willReturn($extra);
        $io->expects($this->once())
            ->method('writeError')
            ->with($expectedMessage);

        Installer::performTasks($composerEvent);
    }

    /**
     * @param array $extra
     * @dataProvider performTasksDataProvider
     */
    public function testPerformTasks(array $extra)
    {
        $package = $this->getMockBuilder(RootPackageInterface::class)
            ->setMethods(['getExtra'])
            ->getMockForAbstractClass();
        $io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();

        $composer = $this->getMockBuilder(Composer::class)
            ->setMethods(['getPackage'])
            ->getMock();
        /** @var Event|MockObject $composerEvent */
        $composerEvent = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMethods(['getComposer', 'getIo'])
            ->getMock();
        $composerEvent->expects($this->once())
            ->method('getIo')
            ->willReturn($io);

        $composerEvent->expects($this->once())
            ->method('getComposer')
            ->willReturn($composer);
        $composer->expects($this->once())
            ->method('getPackage')
            ->willReturn($package);
        $package->expects($this->once())
            ->method('getExtra')
            ->willReturn($extra);

        Installer::performTasks($composerEvent);
    }
}
