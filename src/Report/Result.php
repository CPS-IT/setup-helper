<?php

namespace CPSIT\SetupHelper\Report;

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

/**
 * Class Result
 */
class Result implements ResultInterface
{
    protected $status = ResultInterface::STATUS_UNKNOWN;
    protected $message = ResultInterface::MESSAGE_UNDEFINED;
    protected $id = 0;

    /**
     * Result constructor.
     * @param string|null $message
     * @param int|null $id
     */
    public function __construct(string $message = null, int $id = null)
    {
        if ($message !== null) {
            $this->message = $message;
        }

        if ($id !== null)
        {
            $this->id = $id;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus():string
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getId():int
    {
        return $this->id;
    }

}
