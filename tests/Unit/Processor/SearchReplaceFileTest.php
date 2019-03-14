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

namespace CPSIT\SetupHelper\Tests\Unit\Processor;

use CPSIT\SetupHelper\Processor\SearchReplaceFile;
use CPSIT\SetupHelper\Task\Dto\FileSearch;
use Composer\IO\IOInterface;
use PHPUnit\Framework\TestCase;

class SearchReplaceFileTest extends TestCase
{
    /**
     * @var SearchReplaceFile
     */
    protected $subject;



    /**
     * {@inheritdoc}
     */
    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $fileSearch = new FileSearch();
        $fileSearch->setPath('mads/*.mad');
        $fileSearch->setSearch('my_var');
        $fileSearch->setReplace('your_var');
        $this->fileSearch=$fileSearch;
        $this->io = $this->getMockBuilder(IOInterface::class)
            ->setMethods(['write', 'writeError'])
            ->getMockForAbstractClass();

        $this->subject = new SearchReplaceFile($this->io,$fileSearch);
    }

    public function testPerformGlobbing(){
        /*
         $globbed_files=SearchReplaceFile::rglob($this->fileSearch->getPath());

        //fwrite(STDERR, print_r($globbed_files, TRUE));
        //$expected_array=array(['1' =>  ]);
        $this->assertNotEmpty($globbed_files);
        $this->subject->perform(); */
        return true;
    }
}
