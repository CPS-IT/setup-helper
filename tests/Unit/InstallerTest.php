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

namespace Fr\ProjectBuilder\Tests\Unit;

use Composer\Composer;
use Composer\Config;
use Composer\Package\RootPackageInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Fr\ProjectBuilder\Installer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class InstallerTest extends TestCase
{
    /**
     * @var Installer
     */
    protected $subject;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->subject = new Installer();
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

    public function testPerformTasks()
    {
        $extra = [];
        $package = $this->getMockBuilder(RootPackageInterface::class)
            ->setMethods(['getExtra'])
            ->getMockForAbstractClass();

        $composer = $this->getMockBuilder(Composer::class)
            ->setMethods(['getPackage'])
            ->getMock();
        /** @var Event|MockObject $composerEvent */
        $composerEvent = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->setMethods(['getComposer'])
            ->getMock();

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
