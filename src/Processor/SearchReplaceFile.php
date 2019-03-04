<?php

namespace CPSIT\SetupHelper\Processor;

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

use Composer\IO\IOInterface;
use CPSIT\SetupHelper\Task\Dto\FileSearch;
use CPSIT\SetupHelper\Task\Dto\SearchInterface;
use CPSIT\SetupHelper\Task\TaskInterface;
use Naucon\File\File;
use Naucon\File\FileWriter;

/**
 * Class SearchReplaceFile
 */
class SearchReplaceFile
{
    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @var FileSearch
     */
    protected $fileSearch;

    protected $files;

    /**
     * SearchReplaceFile constructor.
     * @param IOInterface $io
     * @param SearchInterface|null $search
     */
    public function __construct(IOInterface $io, SearchInterface $search = null)
    {
        $this->io = $io;
        if ($search instanceof FileSearch) {
            $this->fileSearch = $search;
        }
    }

    /**
     *
     * @throws \Naucon\File\Exception\FileException
     * @throws \Naucon\File\Exception\FileWriterException
     */
    public function process()
    {
        $file = new File($this->fileSearch->getPath());
        if (!$file->exists()) {
            $this->io->writeError(
                sprintf(
                    TaskInterface::MESSAGE_FILE_NOT_FOUND,
                    $this->fileSearch->getPath()
                )
            );

            return;
        }

        $fileWriter = new FileWriter($file, 'r+');
        $content = $fileWriter->read();
        $fileWriter->clear();
        $count = 0;
        $fileWriter->write(
            str_replace(
                $this->fileSearch->getSearch(),
                $this->fileSearch->getReplace(),
                $content,
                $count
            )
        );

        $this->io->write(
            sprintf(
                TaskInterface::MESSAGE_REPLACED_IN_FILE,
                $this->fileSearch->getSearch(),
                $fileWriter->getPathname(),
                $count,
                $this->fileSearch->getReplace()
            )
        );
    }
}