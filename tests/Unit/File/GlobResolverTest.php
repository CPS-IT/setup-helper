<?php

namespace CPSIT\SetupHelper\Tests\Unit\File;

use CPSIT\SetupHelper\File\GlobResolver;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
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
 * Class GlobResolverTest
 */
class GlobResolverTest extends TestCase
{

    /**
     * @var GlobResolver|MockObject
     */
    protected $subject;

    /**
     * @var vfsStreamDirectory
     */
    protected $virtualDirectory;


    public function setUp()
    {
        parent::setUp();
        $this->subject = new GlobResolver();
        vfsStreamWrapper::register();
        $this->virtualDirectory = vfsStream::setup();
    }

    public function testPatternCanBeSet()
    {
        $pattern = 'foo';
        $this->subject->setPattern($pattern);
        $this->assertSame(
            $pattern,
            $this->subject->getPattern()
        );
    }


    public function filesDataProvider()
    {
        return [
            'empty pattern, empty result' => [
                vfsStream::url(''), [], []
            ]
        ];
    }

    /**
     * @dataProvider filesDataProvider
     * @param string $pattern
     * @param array $fileStructure
     * @param array $expectedResult
     */
    public function testResolveFindsMatchingFiles(string $pattern, array $fileStructure, array $expectedResult){
        vfsStream::create($fileStructure, $this->virtualDirectory);
        $this->subject->setPattern($pattern);

        $this->markTestSkipped('stream schema vfs:// fails');
        $this->assertSame(
            $expectedResult,
            $this->subject->resolve()
        );
    }
}