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

namespace Peerj\UserSecurityBundle\Component\Authentication\Tracker;

use Symfony\Component\HttpFoundation\Session\Session;
use Peerj\UserSecurityBundle\Manager\SessionManagerInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class ResetTracker
{
    /**
     *
     * @access protected
	 * @var \Peerj\UserSecurityBundle\Manager\SessionManager $sessionManager
     */
    protected $sessionManager;
	
    /**
     *
     * @access protected
	 * @var int $blockForMinutes
     */
	protected $blockForMinutes;

    /**
     *
     * @access public
     * @param \Peerj\UserSecurityBundle\Manager\SessionManagerInterface $sessionManager
	 * @param int $blockForMinutes
     */
    public function __construct(SessionManagerInterface $sessionManager, $blockForMinutes)
    {
		$this->sessionManager = $sessionManager;
		$this->blockForMinutes = $blockForMinutes;
    }

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
	 * @param string $ipAddress
     * @return array
     */
    public function getAttempts($ipAddress)
    {
        // Set a limit on how far back we want to look at failed login attempts.
        $timeLimit = new \DateTime('-' . $this->blockForMinutes . ' minutes');
		return $this->sessionManager->findAllByIpAddressAndLoginAttemptDate($this->getType(), $ipAddress, $timeLimit);
    }

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
	 * @param string $ipAddress
	 * @param string $username
     */
    public function addAttempt($ipAddress, $username)
    {
        // Set the expire time to a bit in the future
        $newExpireTime = new \DateTime('+' . $this->blockForMinutes . ' minutes');

        // Make a note of the failed login.
        $this->sessionManager->newRecord($this->getType(), $ipAddress, $username, $newExpireTime);
    }
	
	private function getType()
	{
		return 'reset';
	}
}