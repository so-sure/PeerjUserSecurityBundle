<?php

/*
 * This file is part of the Peerj UserSecurityBundle
 *
 * (c) Peerj <https://peerj.com/>
 *
 * Available on github <http://www.github.com/Peerj/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Peerj\UserSecurityBundle\Manager;


/**
 *
 * @author Patrick McAndrew <patrick@peerj.com>
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
interface SessionManagerInterface
{
    /**
     *
     * @access public
     * @param string $type
     * @param string $ipAddress
	 * @param \DateTime $timeLimit
     * @return \Peerj\UserSecurityBundle\Manager\SessionManagerInterface
     */
	public function findAllByIpAddressAndLoginAttemptDate($type, $ipAddress, $timeLimit);

    /**
     *
     * @access public
     * @param string $type
     * @param string $ipAddress
     * @param string $username
     * @param \DateTime $timeLimit
     * @return self
     */
    public function newRecord($type, $ipAddress, $username, $timeLimit);
}