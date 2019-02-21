<?php

namespace CPSIT\ProjectBuilder\Report;

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

interface ResultInterface
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    const STATUS_NOTICE = 'notice';
    const STATUS_WARNING = 'warning';
    const STATUS_UNKNOWN = 'unknown';

    const MESSAGE_UNDEFINED = 'message undefined';
    const DEFAULT_MESSAGE_ID = 0;

    /**
     * Returns the result status
     * @return string
     */
    public function getStatus();

    /**
     * Returns the result message
     * @return string
     */
    public function getMessage();

    /**
     * Returns a unique id for the result or 0
     * @return int
     */
    public function getId();
}
